<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\LoginSecurityAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,HubInterface $hub, UserPasswordHasherInterface $userPasswordHasher,UtilisateurRepository $u ,UserAuthenticatorInterface $userAuthenticator, LoginSecurityAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $user = new Utilisateur();
        $us=array('ROLE_s');
        $uc=array('ROLE_c');
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(in_array('ROLE_s',$user->getRoles(),true)){
                $user->setRole('sociéte');
            }
            if(in_array('ROLE_c',$user->getRoles(),true)){
                $user->setRole('candidat');
            }
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $user->setMdpsymfony(
                $form->get('password')->getData()
            );
//            $update = new Update(
//                'http://127.0.0.1:8000/utilisateur/Admin',
//                "[]"
//            );

//            $hub->publish($update);
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    ////////////////////
    ///
    ///
    ///
    ///

}
