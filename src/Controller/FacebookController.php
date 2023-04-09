<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacebookController extends AbstractController
{
    #[Route('/connect/facebook', name: 'connect_facebook_start')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('facebook_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ]);
    }

#[Route('/connect/facebook/check', name: 'connect_facebook_check')]
public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
{
    // ** if you want to *authenticate* the user, then
    // leave this method blank and create a Guard authenticator
    // (read below)

    /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
    $client = $clientRegistry->getClient('facebook_main');

    try {
        // the exact class depends on which provider you're using
        /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
        $user = $client->fetchUser();

        // do something with all this new power!
        // e.g. $name = $user->getFirstName();
        var_dump($user); die;
        // ...
    } catch (IdentityProviderException $e) {
        // something went wrong!
        // probably you should return the reason to the user
        var_dump($e->getMessage()); die;
    }
}

}
