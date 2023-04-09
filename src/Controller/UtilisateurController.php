<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\AdminType;
use App\Form\GoogleFbRoleType;
use App\Form\UtilisateurType;
use App\Repository\AdsRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    //////////////////////////////client Affichage
    #[Route('/client', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $proj = $utilisateurRepository->findclient();
        return $this->render('utilisateur/client/show.html.twig', [
            'ffff' => $proj,]);
    }

    /////////delete client
    #[Route('/client/{id}', name: 'deleteclient')]
    public function deleteeclient(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute('app_utilisateur_index');
    }

    #[Route('/ping', name: 'ping')]
    function callMercure(HubInterface $publisher): Response
    {
        $update = new Update("http://127.0.0.1:8000/utilisateur/Admin",
            "[]");
        $publisher->publish($update);
        return $this->redirectToRoute('app_utilisateur_appadmin');
    }

//////////test template
    #[Route('/mouh', name: 'app_utilisateur_indexxxx', methods: ['GET'])]
    public function test(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/Dashbord/testtemplate.html.twig');
    }

    /*
     *administrateur Affichage
     */
    #[Route('/Admin', name: 'app_utilisateur_appadmin', methods: ['GET'])]
    public function adminAf(UtilisateurRepository $utilisateurRepository, Request $request): Response
    {
        return $this->render('utilisateur/Admin/show.html.twig', [
            'utilisateurs' => $utilisateurRepository->findadmin(),
        ]);
    }

    /////////ajout admin
    #[Route('/newAdmin', name: 'app_utilisateur_new_Admin', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, SluggerInterface $slugger, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $utilisateur = new Utilisateur();
        $u = array('ROLE_Admin');
        $us = ('Admin');
        $form = $this->createForm(AdminType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admphot = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded

            if ($admphot) {
                $originalFilename = pathinfo($admphot->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $admphot->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $admphot->move(
                        $this->getParameter('Admin_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle Exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $utilisateur->setPhoto($newFilename);
            }
            $utilisateur->setRoles($u);
            $utilisateur->setRole($us);
            $utilisateur->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateur,
                    $form->get('password')->getData()
                )
            );
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_appadmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/Admin/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    ////////////////////Edit proflie coté adminn
    #[Route('/profile/admin', name: 'profileEditAdmin', methods: ['GET'])]
    public function fer(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/Admin/profile.html.twig');
    }


/////////////edit admin
    #[Route('/{id}/edit', name: 'app_utilisateur_edit_admin', methods: ['GET', 'POST'])]
    public function editt(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(AdminType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_appadmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/Admin/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

/////////delete admin
    #[Route('/{id}', name: 'delete')]
    public function deletee(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute('app_utilisateur_appadmin');
    }

    //////////////////////

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/error/404', name: 'app_utilisateur_error', methods: ['GET'])]
    public function error404(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/error404.html.twig');
    }


    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, $id): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $utilisateurRepository->remove($utilisateur, true);
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }


    //////////////stat
    ///
    #[Route('/Dasboard/stat/ds', name: 'app_utilisateur_dashboard', methods: ['GET'])]
    public function dashboard(AdsRepository $adsRepository, UtilisateurRepository $utilisateurRepository, ChartBuilderInterface $chartBuilder, AdsRepository $repo): Response
    {
        $na = $utilisateurRepository->countuser();
        $nadminn = $utilisateurRepository->countAdmin();
        $ns = $utilisateurRepository->countsociete();
        $nc = $utilisateurRepository->countcandidat();

        $nG = $utilisateurRepository->countfbgoogle();
        $nadmin = $na - $ns - $nc;
        $ntotal = $ns + $nc + $nG;

        $adV = $adsRepository->countadsV();
        $adNV = $adsRepository->countadsNV();
        $adGold = $adsRepository->countgold();
        $adBronze = $adsRepository->countbronze();
        $adG = $adsRepository->countgratuit();
        $ads = $adsRepository->findAll();
        $ns1 = ($ns / $na) * 100;
        $nadmin1 = ($nadminn / $na) * 100;
        $nadmin1 = round($nadmin1, 2);
        $nc1 = ($nc / $na) * 100;
        $nG1 = ($nG / $na) * 100;
        $ns1 = round($ns1, 2);
        $ntotal1 = ($ntotal / $na) * 100;
        $ntotal1 = round($ntotal1, 2);
        $nc1 = round($nc1, 2);
        $nG1 = round($nG1, 2);
        $u = $repo->findAll();

        return $this->render('utilisateur/Dashbord/faceAPpstat.html.twig',
            [
                'nadmin' => '$nadmin',
                'ntotal' => $ntotal,
                'ntotal1' => $ntotal1,
                'ng' => $nG,
                'ng1' => $nG1,
                'nadminn' => $nadminn,
                'nadmin1' => $nadmin1,
                'naa' => $na,
                'ns1' => $ns1,
                'nss' => $ns,
                'ncc' => $nc,
                'nc1' => $nc1,
                'adV' => $adV,
                'adNV' => $adNV,
                'adGold' => $adGold,
                'adBronze' => $adBronze,
                '$adG' => $adG,
                '$ads' => $ads,
            ]);
    }

    ////////////////////////////GoogleFb Role Modification
    #[Route('/role/r', name: 'app_utilisateur_edit_Fb', methods: ['GET', 'POST'])]
    public function editFb(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, $id): Response
    {
        $form = $this->createForm(GoogleFbRoleType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateurRepository->save($utilisateur, true);

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/editFbG.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
            'form' => $form,
        ]);
    }
}
