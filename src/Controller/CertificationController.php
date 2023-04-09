<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\Certification;
use App\Repository\BadgeRepository;
use App\Repository\CertificationRepository;
use App\Form\CertificationType;
use App\Repository\QuizRepository;
use App\Repository\UtilisateurRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;



#[Route('/certification')]
class CertificationController extends AbstractController
{
    #[Route('/', name: 'app_certification_index', methods: ['GET'])]
    public function index(CertificationRepository $certificationRepository,BadgeRepository $br): Response
    {
        $b=count($br->findAll());

        $sem=$certificationRepository->stat_semaine_stat() ;
        $stat=$certificationRepository->stat_count_certif();
        $uc=count($certificationRepository->stat_count_user());
        $moy=($b/$uc);
        $f=$certificationRepository->cert_all();

        return $this->render('certification/index.html.twig', [
            'certifications' => $certificationRepository->cert_all(),
            'stat'=>$stat,
            'uc'=>$uc,
            'sem'=>$sem,
            'badge'=>$b,
            'moy'=>$moy,
        ]);
    }

    #[Route('/u/{r}', name: 'app_certification_indexu', methods: ['GET'])]
    public function indexu(UtilisateurRepository $utilisateurRepository,CertificationRepository $certificationRepository,FlashyNotifier $flashy,$r): Response
    {
        if($r==1)
        {
            $flashy->mutedDark('Le temps est révolu !, Bonne chance la prochaine fois. ');
        }
        else if($r==2)
        {
            $flashy->success('Félicitations vous allez recevoir un E-Mail de certification !');
        }
        else if($r==3)
        {
            $flashy->error('Votre essai est faux, Bonne chance la prochaine fois.');
        }

        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $k=$user->getId();
        return $this->render('certification/indexU.html.twig', [
            'certif' => $certificationRepository->cert_aff($k),
        ]);
    }


    #[Route("/searchCertification ", name:"searchcertif")]
    public function searchCertification(Request $request,NormalizerInterface $Normalizer,CertificationRepository $repository)
    {
        $requestString=$request->get('searchValue');
        $res = $repository->cert_search($requestString);
        $jsonContent = $Normalizer->normalize($res, 'json',['groups'=>'certifications']);
        $retour=json_encode($jsonContent);
        $retour=u($retour)->replace('<<','');
        return new Response($retour);
    }


    #[Route('/aff/{id}', name: 'app_quiz_afficher', methods: ['GET'])]
    public function certif_aff(CertificationRepository $certificationRepository,QuizRepository $qr,$id): Response
    {
        $k=$id;
        $quiz=$qr->findAll();
        return $this->render('certification/affecterquiz.html.twig', [
            'quiz'=>$quiz,
            'cer'=>$k
        ]);
    }


    #[Route('/new', name: 'app_certification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CertificationRepository $certificationRepository,SluggerInterface $slugger): Response
    {
        $certification = new Certification();
        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lien = $form->get('lien')->getData();
            if ($lien) {
                $originalFilename = pathinfo($lien->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $lien->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $lien->move(
                        $this->getParameter('cert_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $certification->setLien($newFilename);


            }
            $time = new \DateTime();
            $t=$time->format('Y/m/d');
            var_dump($t);
            $certification->setDateajout($t);
            $certificationRepository->save($certification, true);

            return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certification/new.html.twig', [
            'certification' => $certification,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_certification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Certification $certification, CertificationRepository $certificationRepository,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CertificationType::class, $certification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lien = $form->get('lien')->getData();
            if ($lien) {
                $originalFilename = pathinfo($lien->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $lien->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $lien->move(
                        $this->getParameter('cert_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $certification->setLien($newFilename);


            }
            $time = new \DateTime();
            $t=$time->format('Y/m/d');
            $certification->setDateajout($t);
            $certificationRepository->save($certification, true);

            return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certification/edit.html.twig', [
            'certification' => $certification,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/{idc}', name: 'app_quiz_affecter', methods: ['GET'])]
    public function quiz_aff(CertificationRepository $certificationRepository,$id,$idc,QuizRepository $qr): Response
    {
        $quiz=$qr->find($id);
        $certif=$certificationRepository->find($idc);
        $certif->setIdQuiz($quiz);
        $certificationRepository->save($certif,true);
        return $this->redirectToRoute('app_certification_index');
    }
    #[Route('/{id}', name: 'app_certification_delete', methods: ['POST'])]
    public function delete(Request $request, Certification $certification, CertificationRepository $certificationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$certification->getId(), $request->request->get('_token'))) {
            $certificationRepository->remove($certification, true);
        }

        return $this->redirectToRoute('app_certification_index', [], Response::HTTP_SEE_OTHER);
    }
}
