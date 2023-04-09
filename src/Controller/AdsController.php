<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Entity\AdsLike;
use App\Entity\Utilisateur;
use App\Form\AdsNombreType;
use App\Repository\AdsLikeRepository;
use App\Repository\AdsRepository;
use App\Form\AdsType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

#[Route('utilisateur/ads')]
class AdsController extends AbstractController
{
    /////permet de liker ou unliker ads
    #[Route('/ads/{id}/like', name: 'ads_like')]
    public function like(Ads $ads,ManagerRegistry $managerRegistry,AdsLikeRepository $adsLikeRepository): Response
    {
        $user = $this->getUser();
        if(!$user) return $this->json([
          'code' => 403,
          'message' =>'il faut connecter'
        ],403);
        if($ads->isLikedByUser($user))
        {
            $like=$adsLikeRepository->findOneBy([
                'ads' =>$ads,
                'user' =>$user
            ]);
            $em = $managerRegistry->getManager();
            $em->remove($like);
            $em->flush();
            return  $this->json([
                'code'=>200,
                'message'=>'like bien supp',
                'likes'=>$adsLikeRepository->count(['ads'=>$ads])
            ],200);
        }
        $like=new AdsLike();
        $like->setAds($ads);
        $like->setUser($user);
        $em = $managerRegistry->getManager();
        $em->persist($like);
        $em->flush();
        return  $this->json([
            'code'=>200,
            'message'=>'ca marche bien',
            'likes'=>$adsLikeRepository->count(['ads'=>$ads])
        ],200);
    }
    #[Route('/', name: 'app_ads_index', methods: ['GET'])]
    public function index(AdsRepository $adsRepository): Response
    {
        $ads=$adsRepository->findonlyValid();
        $ads1=$adsRepository->findonlyValid1();
        $adss=$adsRepository->findonlyValidd();
        $adsss=$adsRepository->findonlyValidd();
        return $this->render('ads/index.html.twig', [
            'ads' => $ads,
            'ads1' => $ads1,
            'adss' => $adss,
            'adsss' => $adsss,
        ]);
    }
    #[Route('/ads/s/{id}', name: 'app_ads_status', methods: ['GET'])]
    public function status(AdsRepository $adsRepository,Ads $ads,$id): Response
    {

        $u=$adsRepository->findonlyValid($id);
        $ads->setStatus(0);
        $adsRepository->save($ads, true);
        return $this->redirectToRoute('app_ads_index');
    }


    #[Route('/new', name: 'app_ads_new', methods: ['GET', 'POST'])]
    public function new(Request $request,AdsLikeRepository $adsLikeRepository ,AdsRepository $adsRepository,SluggerInterface $slugger,Recaptcha3Validator $recaptcha3Validator): Response
    {
        $ad = new Ads();
        $user = new Utilisateur();
        $users[]=$user;
        $session = new Session();

        $like= new AdsLike();
        $form = $this->createForm(AdsType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /////////
            $adsphot = $form->get('photo')->getData();

          //  $donnees = $form->getData();
         //   $datefin = $adsRepository->findOneBy(['dateFin' => $donnees]);
       //     if ($datefin < 'CURRENT_DATE()') {
        //        echo('CURRENT_DATE()');
           //     $this->addFlash('danger', 'cette date n\'existe pas');
           //     return $this->redirectToRoute("app_ads_new");
          //  }

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded

                if ($adsphot) {
                    $originalFilename = pathinfo($adsphot->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $adsphot->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $adsphot->move(
                            $this->getParameter('ads_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle Exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $ad->setPhoto($newFilename);
                }

                $ad->setStatus(1);

                $ad->setNombreAds(1);
                $score = $recaptcha3Validator->getLastResponse()->getScore();
                $adsRepository->save($ad, true);
                $like->setAds($ad);
                $like->setUser($this->getUser());
                $adsLikeRepository->save($like, true);
                return $this->redirectToRoute('app_ads_index', [], Response::HTTP_SEE_OTHER);
            }

                return $this->renderForm('ads/new.html.twig', [
                    'ad' => $ad,
                    'form' => $form,
                ]);

    }

    #[Route('/{id}', name: 'app_ads_show', methods: ['GET'])]
    public function show(Ads $ad,AdsRepository $adsRepository,String $id): Response
    {

        return $this->render('ads/show.html.twig', [
            'ads' => $adsRepository->find($id),
        ]);

    }

       //  $a=$adsrepository->find($id);
       // $uti=$ads->setNombreAds(2);



    #[Route('/ads/d/{id}', name: 'app_utilisateur_adsnumber')]
    public function  adsNumberr(TexterInterface $texter,Request $request, Ads $ads,\Swift_Mailer $mailer, AdsRepository $adsrepo,ManagerRegistry $doctrine,$id,NotifierInterface $notifier): Response
    {

        $em = $doctrine->getManager();
        $a=$adsrepo->find($id);
        $uti=$ads->getNombreAds($id);
        $df=$ads->getDateFin();
        $c=$ads->getStatus(0);

        if (1 == $uti) {
            $uti=$ads->setNombreAds(2);
            $date = $ads->getDateFin();
            $date = date_modify($date,"+1 months");;
            $u=$date->format('Y-m-d');
            $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
            $us=$ads->setDateFin( $dobReconverted);
            $em->flush();
        } elseif (2 == $uti) {
            $uti=$ads->setNombreAds(3);
            $date = $ads->getDateFin();
            $date = date_modify($date,"+1 months");;
            $u=$date->format('Y-m-d');
            $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
            $us=$ads->setDateFin( $dobReconverted);
            $em->flush();
        }
        elseif (3 == $uti) {
            $uti=$ads->setNombreAds(4);
            $date = $ads->getDateFin();
            $date = date_modify($date,"+1 months");;
            $u=$date->format('Y-m-d');
            $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
            $us=$ads->setDateFin( $dobReconverted);
            $em->flush();
        }
        elseif (4 == $uti) {
            $uti=$ads->setNombreAds(5);
            $date = $ads->getDateFin();
            $date = date_modify($date,"+1 months");;
            $u=$date->format('Y-m-d');
            $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
            $us=$ads->setDateFin( $dobReconverted);
            $em->flush();
        }
        elseif (5 == $uti) {
            $uti = $ads->setNombreAds(6);
            $date = $ads->getDateFin();
            $date = date_modify($date, "+1 months");;
            $u = $date->format('Y-m-d');
            $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
            $us = $ads->setDateFin($dobReconverted);
            $em->flush();
        }
            else if(6 == $uti){
                $sms = new SmsMessage(
// the phone number to send the SMS message to
                    $ads->getPhonenumber(),
// the message
                    'Vous allez recevoir un cadeau sur votre  mail 
        https://mail.google.com/mail'
                );
                $message = (new \Swift_Message('Gift'))
                    ->setFrom('mohsen.fennira@esprit.tn')
                    ->setTo($ads->getMail())
                    ->setBody(
                       ' ...', 'text/html'
                    );
                $mailer->send($message);
                $sentMessage = $texter->send($sms);
                $uti=$ads->setNombreAds(7);
                $date = $ads->getDateFin();
                $date = date_modify($date,"+2 months");;
                $u=$date->format('Y-m-d');
                $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
                $us=$ads->setDateFin( $dobReconverted);
                $em->flush();
                // Create a Notification that has to be sent
                // using the "email" channel

            }

        else  {
            $uti=$ads->setNombreAds(8);
            $date = $ads->getDateFin();
            $date = date_modify($date,"+1 months");;
            $u=$date->format('Y-m-d');
            $dobReconverted = \DateTime::createFromFormat('Y-m-d', $u);
            $us=$ads->setDateFin( $dobReconverted);
            $em->flush();
        }
        return $this->redirectToRoute('app_ads_index');
    }

    #[Route('/edit', name: 'app_ads_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ads $ads, AdsRepository $adsRepository): Response
    {
            $form = $this->createForm(AdsType::class, $ads);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adsRepository->save($ads, true);
            return $this->redirectToRoute('app_ads_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ads/index.html.twig', [
            'ads' => $ads,
            'form' => $form,
        ]);
    }

    #[Route('/ads/{id}', name: 'deleteads')]
    public function deleteads(Request $request, Ads $ads, AdsRepository $adsrepo,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($ads);
        $em->flush();
        return $this->redirectToRoute('app_ads_index');
    }
    #[Route('/show/showads/', name: 'app_ads_showads', methods: ['GET'])]
    public function showads(AdsRepository $adsRepository,Request $request): Response
    {
        $u=$adsRepository->findonlyValid();
        return $this->render('ads/afAds.html.twig',['ads'=>$u]);
    }



}






