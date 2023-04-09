<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Test;
use App\Form\OffreEmploiType;
use App\Form\OffreFreelancerType;
use App\Form\OffreStageType;
use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;
use App\Repository\TestRepository;
use App\Repository\UtilisateurRepository;
use App\Service\mailerService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


#[Route('/offre')]
class OffreController extends AbstractController
{
    public static string $type = '';


    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository, OffreRepository $offreRepository): Response
    {

        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $offres = $offreRepository->findBy(['idSoc' => $user->getId()]);
        foreach ($offres as $o) {
            $o->setNbCandidature($offreRepository->findByIdAndCountCand($o->getId()));
        }
        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/all/{id}', name: 'app_offre_cand', methods: ['GET'])]
    public function showCand(Offre $offre, OffreRepository $offreRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $candidatures = $offreRepository->findAllCandidates3($offre->getId());
        $tite = $offre->getTitre();
        return $this->render('societe_candidature/index.html.twig', [
            'candidatures' => $candidatures,
            'offre' => $tite
        ]);
    }

    #[Route('/choix/type', name: 'app_offre_show_typeOffre', methods: ['GET'])]
    public function index1(): Response
    {
        return $this->render('offre/newOffre.html.twig',
            ['types' => ['Stage', 'Emploi', 'Freelancer']]);
    }


    /**
     * @throws ExceptionInterface
     * @throws JsonException
     */
    #[Route('/new/{type}', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new($type, Request $request, OffreRepository $offreRepository,
                        UtilisateurRepository $repository, TestRepository $repositorytest,
                        mailerService $mailer): Response
    {
        $user = $repository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        if ($type === 'Stage') {


            $offre = new Offre();
            $form1 = $this->createForm(OffreStageType::class, $offre);

            $form1->handleRequest($request);

            if ($form1->isSubmitted() && $form1->isValid()) {


                //get user
                $soc = $repository->find($user->getId());
                $offre->setIdSoc($soc);

                //get request values
                $requete1 = $request->request->all();
                if ($requete1['offre_stage']['test'][0]['lien'] ?? null) {
                    //create test
                    $test = new Test();
                    $test->setIdSoc($soc);
                    $test->setLien($requete1['offre_stage']['test'][0]['lien']);
                    $test->setTitre($offre->getTitre());
                    $repositorytest->save($test, true);
                    $offre->setTest($test);
                }
                //create offer
                $offre->setDomaine('Info');
                $offre->setTypeoffre('Stage');
                $offre->setDateajout(date('Y-m-d'));
                foreach ($offreRepository->findBy(['idSoc' => $user->getId()]) as $unique) {
                    if ($unique->getTitre() === $offre->getTitre() &&
                        $unique->getTypeoffre() === $offre->getTypeoffre() &&
                        $unique->getDateexpiration() === $offre->getDateexpiration()) {
                        if ($test ?? null) {
                            $repositorytest->remove($test);
                        }

                        $this->addFlash('unique', 'Vous avez deja crée une offre similaire');
                        return $this->renderForm('offre/new.html.twig', [
                            'offre' => $offre,
                            'form' => $form1,
                            'type' => $type
                        ]);
                    }
                }
                if ($offre->getDateexpiration() <= date('Y-m-d')) {
                    $this->addFlash('warning', 'date invalide');
                    return $this->renderForm('offre/new.html.twig', [
                        'offre' => $offre,
                        'form' => $form1,
                        'type' => $type
                    ]);
                }

                $offreRepository->save($offre, true);
                try {
                    $mailer->sendEmail($soc->getEmail(), 'Offre de stage crée avec succées', 'Confirmation');
                } catch (TransportExceptionInterface $e) {
                }


                $this->addFlash('success', 'Offre crée avec succées');
                return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);

            }

            return $this->renderForm('offre/new.html.twig', [
                'offre' => $offre,
                'form' => $form1,
                'type' => $type
            ]);
//           // $jsoncontent1=$normalizer->normalize($offre,'array',['offre'=>'offres']);
//            $jsoncontent2=$normalizer->normalize($form1,'array',['form'=>'form']);
//                $resultat= json_encode(array_merge(
//                    //(array)json_encode($jsoncontent1, JSON_THROW_ON_ERROR | true),
//                    (array)json_encode($jsoncontent2, JSON_THROW_ON_ERROR | true),
//                    (array)json_encode($type, JSON_THROW_ON_ERROR | true)
//                ), JSON_THROW_ON_ERROR | true);

//            return new JsonResponse(array('form'=>$form1,
//                'output'=> $this->renderView('offre/_form.html.twig'),
//
//                'offre'=>$offre,
//                'type'=>$type));


        }

        if ($type === 'Emploi') {
            $offre = new Offre();
            $form1 = $this->createForm(OffreEmploiType::class, $offre);
            $form1->handleRequest($request);
            if ($form1->isSubmitted() && $form1->isValid()) {
                $requete1 = $request->request->all();
                /*
                                $brochureFile = $requete1['offre_emploi']['test'][0]['lien'];


                                // this condition is needed because the 'brochure' field is not required
                                // so the PDF file must be processed only when a file is uploaded
                                if ($brochureFile) {
                                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                                    // this is needed to safely include the file name as part of the URL
                                    $safeFilename = $slugger->slug($originalFilename);
                                    $newFilename = $safeFilename . '-' . uniqid('', true) . '.' . $brochureFile->guessExtension();

                                    // Move the file to the directory where brochures are stored
                                    try {
                                        $brochureFile->move(
                                            $this->getParameter('Upload_directory'),
                                            $newFilename
                                        );
                                    } catch (FileException $e) {
                                        // ... handle exception if something happens during file upload
                                    }

                                    // updates the 'brochureFilename' property to store the PDF file name
                                    // instead of its contents

                                }

                */
                //get user
                $soc = $repository->find($user->getId());
                $offre->setIdSoc($soc);

                //get request values
                if ($requete1['offre_emploi']['test'][0]['lien'] ?? null) {
                    //create test
                    $test = new Test();
                    $test->setIdSoc($soc);
                    $test->setLien($requete1['offre_emploi']['test'][0]['lien']);
                    $test->setTitre($offre->getTitre());

                    $repositorytest->save($test, true);
                    $offre->setTest($test);
                }
                //create offer
                $offre->setDomaine('Info');
                $offre->setTypeoffre('Emploi');
                $offre->setDateajout(date('Y-m-d'));
                foreach ($offreRepository->findBy(['idSoc' => $user->getId()]) as $unique) {
                    if ($unique->getTitre() === $offre->getTitre() && $unique->getTypeoffre() === $offre->getTypeoffre() && $unique->getDateexpiration() === $offre->getDateexpiration()) {
                        if ($test ?? null) {
                            $repositorytest->remove($test);
                        }
                        $this->addFlash('unique', 'Vous avez deja crée une offre similaire');
                        return $this->renderForm('offre/new.html.twig', [
                            'offre' => $offre,
                            'form' => $form1,
                            'type' => $type
                        ]);
                    }
                }
                if ($offre->getDateexpiration() <= date('Y-m-d')) {
                    $this->addFlash('warning', 'date invalide');
                    return $this->renderForm('offre/new.html.twig', [
                        'offre' => $offre,
                        'form' => $form1,
                        'type' => $type
                    ]);
                }
                $offreRepository->save($offre, true);
                try {
                    $mailer->sendEmail($soc->getEmail(), "Offre d'emploi crée avec succées", 'Confirmation');
                } catch (TransportExceptionInterface $e) {
                }
                $this->addFlash('success', 'Offre crée avec succées');

                return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
            }

            // $this->resultat="Emploi";
            return $this->renderForm('offre/new.html.twig', [
                'offre' => $offre,
                'form' => $form1,
                'type' => $type

            ]);

        }

        if ($type === 'Freelancer') {
            $offre = new Offre();
            $form1 = $this->createForm(OffreFreelancerType::class, $offre);
            $form1->handleRequest($request);


            if ($form1->isSubmitted() && $form1->isValid()) {
                //get user
                $soc = $repository->find($user->getId());
                $offre->setIdSoc($soc);

                //get request values
                $requete1 = $request->request->all();

                if ($requete1['offre_freelancer']['test'][0]['lien'] ?? null) {
                    //create test
                    $test = new Test();
                    $test->setIdSoc($soc);
                    $test->setLien($requete1['offre_freelancer']['test'][0]['lien']);
                    $test->setTitre($offre->getTitre());
                    $repositorytest->save($test, true);
                    $offre->setTest($test);

                }

                //create offer
                $offre->setDomaine('Info');
                $offre->setTypeoffre('Freelancer');
                $offre->setDateajout(date('Y-m-d'));
                foreach ($offreRepository->findBy(['idSoc' => $user->getId()]) as $unique) {
                    if ($unique->getTitre() === $offre->getTitre() && $unique->getTypeoffre() === $offre->getTypeoffre() && $unique->getDateexpiration() === $offre->getDateexpiration()) {
                        if ($test ?? null) {
                            $repositorytest->remove($test);
                        }
                        $this->addFlash('unique', 'Vous avez deja crée une offre similaire');
                        return $this->renderForm('offre/new.html.twig', [
                            'offre' => $offre,
                            'form' => $form1,
                            'type' => $type
                        ]);
                    }
                }
                if ($offre->getDateexpiration() <= date('Y-m-d')) {
                    $this->addFlash('warning', 'date invalide');
                    return $this->renderForm('offre/new.html.twig', [
                        'offre' => $offre,
                        'form' => $form1,
                        'type' => $type
                    ]);
                }
                $offreRepository->save($offre, true);
                try {
                    $mailer->sendEmail($soc->getEmail(), 'Offre Freelancer crée avec succées', 'Confirmation');
                } catch (TransportExceptionInterface $e) {
                }
                $this->addFlash('success', 'Offre crée avec succées');

                return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
            }

            // $this->resultat="Freelancer";
            return $this->renderForm('offre/new.html.twig', [
                'offre' => $offre,
                'form' => $form1,
                'type' => $type

            ]);

        }
        return new Response();
    }


    #[Route('/show/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        if ($offre->getTest()) {
            $testOrnull = "Oui";
        } else {
            $testOrnull = "Non";
        }
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
            'test' => $testOrnull
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, TestRepository $testRepository, OffreRepository $offreRepository): Response
    {
        if ($offre->getTypeoffre() === "Freelancer") {
            $form = $this->createForm(OffreFreelancerType::class, $offre);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $requete1 = $request->request->all();

                if ($requete1['offre_freelancer']['test'][0]['lien'] ?? null) {
                    //create test
                    $test = new Test();
                    $test->setIdSoc($offre->getIdSoc());
                    $test->setLien($requete1['offre_freelancer']['test'][0]['lien']);
                    $test->setTitre($offre->getTitre());

                    $testRepository->save($test, true);
                    $offre->setTest($test);

                }


                $offreRepository->save($offre, true);
                $this->addFlash('update', 'Offre modifiée avec succées');

                return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        if ($offre->getTypeoffre() === "Stage") {
            $form = $this->createForm(OffreStageType::class, $offre);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $requete1 = $request->request->all();
                if ($requete1['offre_stage']['test'][0]['lien'] ?? null) {
                    //create test
                    $test = new Test();
                    $test->setIdSoc($offre->getIdSoc());
                    $test->setLien($requete1['offre_stage']['test'][0]['lien']);
                    $test->setTitre($offre->getTitre());
                    $testRepository->save($test, true);
                    $offre->setTest($test);
                }

                $offreRepository->save($offre, true);
                $this->addFlash('update', 'Offre modifiée avec succées');

                return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        if ($offre->getTypeoffre() === "Emploi") {
            $form = $this->createForm(OffreEmploiType::class, $offre);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $requete1 = $request->request->all();
                if ($requete1['offre_emploi']['test'][0]['lien'] ?? null) {
                    //create test
                    $test = new Test();
                    $test->setIdSoc($offre->getIdSoc());
                    $test->setLien($requete1['offre_emploi']['test'][0]['lien']);
                    $test->setTitre($offre->getTitre());
                    $testRepository->save($test, true);
                    $offre->setTest($test);
                }

                $offreRepository->save($offre, true);
                $this->addFlash('update', 'Offre modifiée avec succées');

                return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        if ($offre->getTest()) {
            $testOrnull = $offre->getTest()->getId();
        } else {
            $testOrnull = -1;
        }
        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
            'type' => $offre->getTypeoffre(),
            'test' => $testOrnull
        ]);
    }

    #[Route('/delete/{id}', name: 'app_offre_delete')]
    public function delete(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {

        $offreRepository->remove($offre, true);
        $this->addFlash('delete', 'Offre supprimée avec succées');

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_offre_ajout_test', methods: ['POST'])]
    public function newTest(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $offre->setTest();

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);

    }


    /**
     * @throws ExceptionInterface
     * @throws JsonException
     */
    #[Route('/offresearch', name: 'search')]
    public function searchQB(UtilisateurRepository $utilisateurRepository,NormalizerInterface $normalizer, OffreRepository $repository, Request $request)
    {
        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $requeststring = $request->get('search2');
        $offres = $repository->findOffresBytitre($requeststring,$user->getId());
        foreach ($offres as $o) {
            $o->setNbCandidature($repository->findByIdAndCountCand($o->getId()));
        }

        $jsoncontent = $normalizer->normalize($offres, 'json', ['offres' => 'offres']);
        $retour = json_encode($jsoncontent, JSON_THROW_ON_ERROR);
        return new Response($retour);
    }

    #[Route('/offresearch/{type}', name: 'app_offre_typeOffreSearch')]
    public function typeOffreTri(UtilisateurRepository $utilisateurRepository,$type, NormalizerInterface $normalizer, OffreRepository $repository, Request $request)
    {
        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        // $requeststring=$request->get('search2');
        if ($type === 'All') {
            $offres = $repository->findBy(['idSoc' => $user->getId()]);
            foreach ($offres as $o) {
                $o->setNbCandidature($repository->findByIdAndCountCand($o->getId()));
            }

            $jsoncontent = $normalizer->normalize($offres, 'json', ['offres' => 'offres']);
            $retour = json_encode($jsoncontent, JSON_THROW_ON_ERROR);
            return new Response($retour);
        }


        $offres = $repository->findBy(['idSoc' =>59, 'typeoffre' => $type]);
        foreach ($offres as $o) {
            $o->setNbCandidature($repository->findByIdAndCountCand($o->getId()));
        }

        $jsoncontent = $normalizer->normalize($offres, 'json', ['offres' => 'offres']);
        $retour = json_encode($jsoncontent, JSON_THROW_ON_ERROR);
        return new Response($retour);

    }

    //Admin
    #[Route('/admin', name: 'app_utilisateur_ilyes')]
    public function ilyes(OffreRepository $offreRepository, CandidatureRepository $candidatureRepository): Response
    {
        $nb = $offreRepository->findNBoffresAdmin();
        $u = $offreRepository->findAll();
        $offreBydate = $offreRepository->findOffreByDate();
        $cands = count($candidatureRepository->findAll());
        $lastmonth = count($offreRepository->findOffreOfLastMonth());
        $lastweek = count($offreRepository->findOffreLastWeek());
        //conditions a verifier
//            if($lastweek == 0){
//                $pourcentageMois=null;
//            }
//        //conditions a verifier
//        if(!$lastmonth == 0){
//                $pourcentageWeek=null;
//            }

        if ($lastmonth > 0) {
            $pourcentageMois = ((count($offreRepository->findOffreByMonth()) / $lastmonth) - 1) * 100;
        } else {
            $pourcentageMois = 0;
        }
        if ($lastweek > 0) {
            $pourcentageWeek = ((count($offreRepository->findOffreByWeek()) / $lastweek) - 1) * 100;

        } else {
            $pourcentageWeek = 0;
        }
        $stageTab = '';
        //type Stage
        $i = 1;
        while ($i < 13) {
            $stageTab .= count($offreRepository->findOffreByMonthDiff($i, 'Stage')) . ', ';
            $i++;

        }
        //type freelancer
        $FreelancerTab = '';
        $j = 1;
        while ($j < 13) {
            $FreelancerTab .= count($offreRepository->findOffreByMonthDiff($j, 'Freelancer')) . ', ';
            $j++;

        }
        //type Emploi
        $EmploiTab = '';
        $k = 1;
        while ($k < 13) {
            $EmploiTab .= count($offreRepository->findOffreByMonthDiff($k, 'Emploi')) . ', ';
            $k++;

        }

        //

        return $this->render('offre/templateAdmin.html.twig', [
            'offres' => $u,
            'offreTri' => $offreBydate,
            'nb' => $nb,
            'week' => count($offreRepository->findOffreByWeek()),
            'month' => count($offreRepository->findOffreByMonth()),
            'Pmonth' => $pourcentageMois,
            'Pweek' => $pourcentageWeek,
            'Yoffre' => count($offreRepository->findOffreByYear()),
            'Mothname' => count($offreRepository->findOffreByMonthDiff(12, 'Stage')),
            'stage' => $stageTab,
            'freelancer' => $FreelancerTab,
            'emploi' => $EmploiTab,
            'cands' => $cands


        ]);
    }

}
