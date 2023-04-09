<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\FbGoogleType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Security\LoginSecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class GoogleController extends AbstractController
{
    #[Route('/google', name: 'app_google')]
    public function index(): Response
    {
        return $this->render('google/index.html.twig', [
            'controller_name' => 'GoogleController',
        ]);
    }
    #[Route('/connect/google', name: 'connect_google')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('google_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect();
    }
    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectCheckAction(Request $request){
        if(!$this->getUser()){
            return new  JsonResponse(array('status' => false, 'message' => "user not found"));
        }
        else {
            return  $this->redirectToRoute('dashbord');
        }
    }
    ///
    #[Route('/registerFbGoogle', name: 'app_register_google_facebook')]
    public function registerGoogleFacebbok(Request $request, UserPasswordHasherInterface $userPasswordHasher,UtilisateurRepository $u ,UserAuthenticatorInterface $userAuthenticator, LoginSecurityAuthenticator $authenticator, EntityManagerInterface $entityManager,Utilisateur $user): Response

    {
        $form = $this->createForm(FbGoogleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $u->save($user, true);

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fbgoogle/register.html.twig', [
            'utilisateur' => $user,
            'form' => $form,
        ]);

    }

}
