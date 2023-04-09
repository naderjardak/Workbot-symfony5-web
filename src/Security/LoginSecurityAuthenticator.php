<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginSecurityAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $mdp = $request->request->get('password', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);



        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($mdp),

            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );


    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        $user=$token->getUser();
        if(in_array('ROLE_s',$user->getRoles(),true)){

            return new RedirectResponse($this->urlGenerator->generate('app_offre_index'));
        }
        else if(in_array('ROLE_c',$user->getRoles(),true)){
            return new RedirectResponse($this->urlGenerator->generate('app_affichageoffre'));
        }
        // For example:
         return new RedirectResponse($this->urlGenerator->generate('app_utilisateur_appadmin'));
      //  throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }



    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') && $this->getLoginUrl($request) === $request->getRequestUri();
    }
}
