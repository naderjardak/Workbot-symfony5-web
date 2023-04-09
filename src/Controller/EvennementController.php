<?php

namespace App\Controller;

use App\Entity\Evennement;
use App\Form\EvennementType;
use App\Repository\EvennementRepository;
use App\Repository\ParticipationRepository;
use App\Repository\SponsorRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/evennement')]
class EvennementController extends AbstractController
{
    #[Route('/', name: 'app_evennement_index', methods: ['GET'])]
    public function index(EvennementRepository $evennementRepository): Response
    {

        return $this->render('evennement/index.html.twig', [
            'evennements' => $evennementRepository->findAll(),
        ]);
    }

    #[Route('/index2', name: 'app_evennement_index2', methods: ['GET'])]
    public function indexp(EvennementRepository $evennementRepository): Response
    {
        $session = new Session();
        $iduser = $session->getId();
        return $this->render('evennement/evennement2.html.twig', [
            'evennements22' => $evennementRepository->participnotin($iduser),
        ]);

    }


    #[Route('/template', name: 'app_evennement_index_temp', methods: ['GET'])]
    public function indextemp(): Response
    {
        return $this->render('utilisateur/test.html.twig', [
        ]);
    }

    #[Route('/new', name: 'app_evennement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EvennementRepository $evennementRepository, UtilisateurRepository $userrepo, MailerInterface $smail, SluggerInterface $slugger): Response
    {
        $evennement = new Evennement();
        $form = $this->createForm(EvennementType::class, $evennement);
        $form->handleRequest($request);

        $user = $userrepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $id = $user->getId();
        $user = $userrepo->find($id);
        $evennement->setIdUser($user);


        if ($form->isSubmitted() && $form->isValid()) {

            $email = (new Email())->from('houssem.bribech@esprit.tn')
                ->to('houssembrib98@gmail.com')
                ->subject('JOB.TN.com')
                ->text('Votre évènement a étè crée avec succés');

            $smail->send($email);
            $flyerphot = $form->get('flyer')->getData();
            $video = $form->get('video')->getData();

            if ($flyerphot) {
                $originalFilename = pathinfo($flyerphot->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $flyerphot->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $flyerphot->move(
                        $this->getParameter('flyer_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $evennement->setFlyer($newFilename);


            }
            if ($video) {
                $originalFilename = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $video->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $video->move(
                        $this->getParameter('video_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $evennement->setVideo($newFilename);


            }
            $evennementRepository->save($evennement, true);
            return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evennement/new.html.twig', [
            'evennement' => $evennement,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_evennement_show', methods: ['GET'])]
    public function show(Evennement $evennement): Response
    {
        return $this->render('evennement/show.html.twig', [
            'evennement' => $evennement,
        ]);
    }

    #[Route('/{id}/voir', name: 'voirpev', methods: ['GET'])]
    public function voir(Evennement $ev, ParticipationRepository $partv, UtilisateurRepository $userrep, $id): Response
    {

        $par = $partv->voirp($id);
        //var_dump($par);
        return $this->render('participation/voir.html.twig', [
            'par' => $par,
        ]);
    }

    #[Route('/{id}/paticiper', name: 'participer', methods: ['GET'])]
    public function participer(Evennement $evennement, EvennementRepository $evennementRepository, ParticipationRepository $partv, UtilisateurRepository $userrepo, $id,/*MailerInterface $smail*/): Response
    {
        $user = $userrepo->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $iduser = $user->getId();





        $partv->particip($id, $iduser);
        $evennementRepository->nbplaceupdate($id);
        $evennements22 = $evennementRepository->participin($iduser);
        // $email=(new Email())->from('houssem.bribech@esprit.tn')
        //->to('houssembrib98@gmail.com')
        //->subject('JOB.TN.com')
        //->text('Votre participation a effectué avec succés');
        //$smail->send($email);
        //var_dump($par);


        return $this->render('evennement/participannule.html.twig', [
            'eventannule' => $evennements22,

        ]);
    }


    #[Route('/{id}/spons', name: 'sponsh', methods: ['GET'])]
    public function spons(SponsorRepository $sponrepo, $id,): Response
    {

        $spons = $sponrepo->voirspon($id);

        return $this->render('evennement/voirspons.html.twig', [
            'spons' => $spons,
            'id' => $id,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evennement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evennement $evennement, EvennementRepository $evennementRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EvennementType::class, $evennement);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $flyerphot = $form->get('flyer')->getData();
            $video = $form->get('video')->getData();

            if ($flyerphot) {
                $originalFilename = pathinfo($flyerphot->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $flyerphot->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $flyerphot->move(
                        $this->getParameter('flyer_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $evennement->setFlyer($newFilename);


            }
            if ($video) {
                $originalFilename = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $video->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $video->move(
                        $this->getParameter('video_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $evennement->setVideo($newFilename);


            }
            $evennementRepository->save($evennement, true);


            return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evennement/edit.html.twig', [
            'evennement' => $evennement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evennement_delete', methods: ['POST'])]
    public function delete(Request $request, Evennement $evennement, EvennementRepository $evennementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evennement->getId(), $request->request->get('_token'))) {
            $evennementRepository->remove($evennement, true);
        }

        return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/annuler', name: 'annule', methods: ['GET'])]
    public function indexannule(EvennementRepository $evennementRepository): Response
    {
        $session = new Session();
        $iduser = $session->getId();
        return $this->render('evennement/participannule.html.twig', [
            'eventannule' => $evennementRepository->participin($iduser),
        ]);

    }

    #[Route('/annuler', name: 'annule', methods: ['GET'])]
    public function indexannule2(EvennementRepository $evennementRepository): Response
    {
        $session = new Session();
        $iduser = $session->getId();
        return $this->render('evennement/participannule.html.twig', [
            'eventannule' => $evennementRepository->participin($iduser),
        ]);

    }

    #[Route('/{id}/paticiperdelete', name: 'participerdelete', methods: ['GET'])]
    public function participerdelete(ParticipationRepository $partv, $id, EvennementRepository $evennementRepository): Response
    {

        $session = new Session();
        $iduser = $session->getId();

        $partv->deleteparticipation($id, $iduser);
        $evennementRepository->annuleupdate($id);

        //var_dump($par);


        return $this->render('evennement/evennement2.html.twig', [
            'evennements22' => $evennementRepository->participnotin($iduser),

        ]);
    }

    /*#[Route('/evvv', name: 'event_partinterface', methods: ['GET'])]
    public function voirevpart(Evennement $ev,ParticipationRepository $partv,UtilisateurRepository $userrep,EvennementRepository $eventrepo): Response
    {
        $evpartshow=$eventrepo->participinter(9);
             return $this->render('evennement/eventpaticipinterface.html.twig', [
                 'evpartshow' => $eventrepo,
             ]);
    }*/
}
