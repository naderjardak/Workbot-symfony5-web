<?php
/*
namespace App\Security;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleUser;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;


class GoogleAuthenticator extends SocialAuthenticator
{

     private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router, UtilisateurRepository $utilisateurRepository)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->getPathInfo() =='/connect/google/check' && $request->isMethod('GET');
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccesToken($this->clientRegistry->getClient('google'));
    }

     public function getUser($credentials, UserProviderInterface $userProvider )
    {
        $googleUser = $this->clientRegistry->getClient('google')
            ->fetchUserFromToken($credentials);
       // return $this->utilisateurRepository-> findorCreateFrom0auth($googleUser);
      //  $email = $googleUser->getEmail();
        $email = $googleUser->getEmail();
        $utilisateur = $this->em->getRepository('App:Utilisateur')
            ->findOneBy(['email' => $email]);
        if(!$utilisateur){
            $user = new Utilisateur();
            $user->setEmail($googleUser->getEmail());
            $this->em->persist($user);
            $this->em->flush();;
        }
return $user;
    }



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        // change "app_homepage" to some route in your app
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey); {
        return new RedirectResponse($targetPath ?: '/');
    }
    return new RedirectResponse($this->urlGenerator->generate('app_login'));
        // or, on success, let the request continue to be handled by the controller
        //return null;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $Exception): ?Response
    {
        $message = strtr($Exception->getMessageKey(), $Exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse('/login');
    }

}
*/
namespace App\Security;

use App\Entity\User; // your user entity
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $router;
    private $repository;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router,UtilisateurRepository $repository)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google_main');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);
                $us=('ROLE_a');
                $email = $googleUser->getEmail();

                // 1) have they logged in with Facebook before? Easy!
                $existingUser = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['googleId' => $googleUser->getId()]);

                if ($existingUser) {
                    return $existingUser;
                }
                /*
                 *
                   */


                // 2) do we have a matching user by email?
              #  $Utilisateur = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);

                // 3) Maybe you just want to "register" them by creating
                // a User object

                 $t=$googleUser->getEmail();
                $u=$this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $t]);

                if(!$u) {
                    $u=array('ROLE_a');
                    $utilisateur = new Utilisateur();
                    $utilisateur->setGoogleId($googleUser->getId());
                    $utilisateur->setEmail($googleUser->getEmail());
                    $utilisateur->setNom($googleUser->getLastName());
                    $utilisateur->setPhotoGoogleFb($googleUser->getAvatar());
                    $utilisateur->setPrenom($googleUser->getFirstName());
                    $utilisateur->setRole( $us);
                    $utilisateur->setRoles( $u);
                    $this->entityManager->persist($utilisateur);
                    $this->entityManager->flush();

                    return $utilisateur;
                }
                else{
                    $u->setGoogleId($googleUser->getId());
                    $this->entityManager->flush();

                    return $u;
                }

            })
        );

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('app_utilisateur_index');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}