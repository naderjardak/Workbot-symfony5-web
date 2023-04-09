<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ForgotPasswordType;
use App\Repository\UtilisateurRepository;
use Swift_Message;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/forgot', name: 'forgot')]
    public function forgotPassword(ManagerRegistry $doctrine,Request $request, UtilisateurRepository $userRepository,\Swift_Mailer $mailer, TokenGeneratorInterface  $tokenGenerator)
    {


        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData();//


            $user = $userRepository->findOneBy(['email'=>$donnees]);
            if(!$user) {
                $this->addFlash('danger','cette adresse n\'existe pas');
                return $this->redirectToRoute("forgot");

            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManger = $doctrine->getManager();
                $entityManger->persist($user);
                $entityManger->flush();




            }catch(\Exception $exception) {
                $this->addFlash('warning','une erreur est survenue :'.$exception->getMessage());
                return $this->redirectToRoute("app_login");


            }

            $url = $this->generateUrl('app_reset_password',array('token'=>$token),UrlGeneratorInterface::ABSOLUTE_URL);
            //-- Path to template
            $template = 'utilisateur/mail/my-awesome-template.html.twig';
            //-- Params to pass to template
            $params = [
                'dynamicContent' => $url
            ];
            //BUNDLE MAILER
            $message = (new Swift_Message('Mot de password oublié'))
                ->setFrom('mohsen.fennira@esprit.tn')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->render($template, $params), 'text/html'
                );


            //send mail
            $mailer->send($message);
            $this->addFlash('message','E-mail  de réinitialisation du mot passe envoyé :');
            return $this->redirectToRoute("app_login");



        }

        return $this->render("security/forgotPassword.html.twig",['form'=>$form->createView()]);
    }




    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return $this->redirectToRoute('app_accueil');
    }

    #[Route(path: '/resetpassword/{token}', name: 'app_reset_password')]
    public function resetpassword(Request $request,string $token, UserPasswordHasherInterface $userPasswordHasher,ManagerRegistry $doctrine)
    {
        $utilisateur = $doctrine->getRepository(Utilisateur::class)->findOneBy(['resetToken'=>$token]);

        if($utilisateur  == null ) {
            $this->addFlash('danger','TOKEN INCONNU');
            return $this->redirectToRoute("app_login");

        }

        if($request->isMethod('POST')) {
            $utilisateur ->setResetToken(null);
            $utilisateur->setPassword($userPasswordHasher->hashPassword($utilisateur,$request->request->get('password')));

            $entityManger = $doctrine->getManager();
            $entityManger->persist($utilisateur);
            $entityManger->flush();

            $this->addFlash('message','Mot de passe mis à jour :');
            return $this->redirectToRoute("app_login");

        }
        else {
            return $this->render("security/resetPassword.html.twig",['token'=>$token]);

        }
    }
}
