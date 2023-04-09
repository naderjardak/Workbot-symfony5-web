<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Offre;
use App\Repository\OffreRepository;
use App\Serializer\Normalizer\ReclamationNormalizer;
use Container7xKI3AC\getNormalizableInterfaceService;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Reclamation;
use App\Form\ReclamationEtatType;
use App\Repository\CategorieRepository;
use App\Repository\ReclamationRepository;
use App\Form\ReclamationType;
use App\Repository\UtilisateurRepository;
use App\Service\mailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

#[Route('/reclamations')]
class ReclamationController extends AbstractController
{
    #[Route('/searchReclamationx', name: 'searchReclamationx')]
    public function searchReclamationx (Request $request, ReclamationNormalizer $normalizer, ReclamationRepository $reclamationRepository)
    {
        $requestString=$request->get('searchValue');
        $reclamtions=$reclamationRepository->findByReclamationByReference($requestString);
        $jsonContent=array();
        for($i=0;$i<count($reclamtions);$i++){$jsonContent[$i]=$normalizer->normalize($reclamtions[$i],'json',[]);}
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }

    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository,Request $request ,ReclamationRepository $reclamationRepository, PaginatorInterface $paginator): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $reclamations = $reclamationRepository->findBy(array('idUtilisateur'=>$user));

        $reclamations = $paginator->paginate(
            $reclamations, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('reclamation/index.html.twig', [
            'reclamation' => $reclamations,
        ]);
    }
    #[Route('/admin', name: 'app_admin_reclamation_index', methods: ['GET'])]
    public function admin_index(Request $request ,ReclamationRepository $reclamationRepository, PaginatorInterface $paginator, CategorieRepository $categorieRepository): Response
    {
        $reclamations = $reclamationRepository->findAll();

        $reclamations = $paginator->paginate(
            $reclamations, /* query NOT result */
            $request->query->getInt('page', 1),
            5
        );
        $etats = array('envoyée', 'en train de traitement', 'traitée');
        $etatCount = [];
        $etatCount[0] = count($reclamationRepository->findBy(array('etat'=>'envoyée')));
        $etatCount[1] = count($reclamationRepository->findBy(array('etat'=>'en cour de traitement')));
        $etatCount[2] = count($reclamationRepository->findBy(array('etat'=>'traitée')));

        $technique=count($reclamationRepository->findReclamationsByCategorie('Technique'));
        $offre=count($reclamationRepository->findReclamationsByCategorie('Offre'));

        $reclamationn = $reclamationRepository->countByDate();

        $dates = [];
        $recCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($reclamationn as $rec){
            $dates[] = $rec['dateReclamations'];
            $recCount[] = $rec['count'];
        }
        return $this->render('reclamation/admin_index.html.twig', [
            'reclamation' => $reclamations,
            'etats' => json_encode($etats),
            'etatCount' => json_encode($etatCount),
            'technique'=>$technique,
            'offre'=>$offre,
            'dates' => json_encode($dates),
            'recCount' => json_encode($recCount),
        ]);
    }
    #[Route('/admin_stat', name: 'app_admin_stat', methods: ['GET'])]
    public function admin_stat(Request $request ,ReclamationRepository $reclamationRepository, CategorieRepository $categorieRepository): Response
    {
        $etats = array('envoyée', 'en train de traitement', 'traitée');
        $etatCount = [];
        $etatCount[0] = count($reclamationRepository->findBy(array('etat'=>'envoyée')));
        $etatCount[1] = count($reclamationRepository->findBy(array('etat'=>'en cour de traitement')));
        $etatCount[2] = count($reclamationRepository->findBy(array('etat'=>'traitée')));

        $technique=count($reclamationRepository->findReclamationsByCategorie('Technique'));
        $offre=count($reclamationRepository->findReclamationsByCategorie('Offre'));

        $reclamations = $reclamationRepository->countByDate();

        $dates = [];
        $recCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($reclamations as $rec){
            $dates[] = $rec['dateReclamations'];
            $recCount[] = $rec['count'];
        }

        return $this->render('reclamation/recstatistique.html.twig', [
            'etats' => json_encode($etats),
            'etatCount' => json_encode($etatCount),
            'technique'=>$technique,
            'offre'=>$offre,
            'dates' => json_encode($dates),
            'recCount' => json_encode($recCount),
            ]);
    }


    #[Route('/newRT', name: 'app_reclamation_newRT', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository, UtilisateurRepository $utilisateurRepository, CategorieRepository $CategorieRepository): Response
    {
        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $reclamation = new Reclamation();
        $reclamation->setIdUtilisateur($user);
        $rt = $CategorieRepository->find(1);
        $reclamation->setIdCategorie($rt);
        $reclamation->setDateajout(date_create('now'));
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->add('envoyer', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $reclamation->getImage();
            if ($file != null) {
                $fileName = md5(uniqid()) . 'Downloads' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }


                $reclamation->setImage($fileName);
            }
            $reclamationRepository->save($reclamation, true);
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('reclamation/newRT.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/newRO/{id}', name: 'app_reclamation_newRO', methods: ['GET', 'POST'])]
    public function newRO(Request $request, ReclamationRepository $reclamationRepository, UtilisateurRepository $utilisateurRepository, CategorieRepository $CategorieRepository, OffreRepository $offreRepository, $id): Response
    {
        $reclamation = new Reclamation();
        $user = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $reclamation->setIdUtilisateur($user);
        $offre= $offreRepository->find($id);
        $reclamation->setIdOffre($offre);
        $rt = $CategorieRepository->find(2);
        $reclamation->setIdCategorie($rt);
        $reclamation->setDateajout(date_create('now'));
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->add('envoyer', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $reclamation->getImage();
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }


                $reclamation->setImage($fileName);
            }
            $reclamationRepository->save($reclamation, true);
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('reclamation/newRT.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    #[Route('/{id}/admin', name: 'app_admin_reclamation_show', methods: ['GET'])]
    public function admin_show(Reclamation $reclamation, ReclamationRepository $reclamationRepository,mailerService $mailer): Response
    {
        if ($reclamation->getEtat()=='envoyée') {
            $reclamation->setEtat('en cour de traitement');
            $reclamationRepository->save($reclamation, true);
            $to=$reclamation->getIdUtilisateur()->getEmail();
            $content='Bonjour '.$reclamation->getIdUtilisateur()->getPrenom().', votre reclamation de reference '.$reclamation->getId().' est '.$reclamation->getEtat().'.';
            $mailer->sendEmail($to, $content);
        }
        return $this->render('reclamation/admin_show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->add('modifier', SubmitType::class);
        $img = $reclamation->getImage();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $reclamation->getImage();
            if ($file != null) {
                $fileName = md5(uniqid()) . 'Downloads' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }


                $reclamation->setImage($fileName);
            } else $reclamation->setImage($img);
            $reclamationRepository->save($reclamation, true);
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit/admin', name: 'app_admin_reclamation_etat', methods: ['GET', 'POST'])]
    public function admin_edit(Reclamation $reclamation, ReclamationRepository $reclamationRepository,MailerService $mailer): Response
    {
            $reclamation->setEtat('traitée');
            $reclamationRepository->save($reclamation, true);
            $to=$reclamation->getIdUtilisateur()->getEmail();
            $content='Bonjour '.$reclamation->getIdUtilisateur()->getPrenom().', votre reclamation de reference '.$reclamation->getId().' est '.$reclamation->getEtat().'.';
            $mailer->sendEmail($to, $content);
            return $this->redirectToRoute('app_admin_reclamation_show', ['id' => $reclamation->getId(),], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

}