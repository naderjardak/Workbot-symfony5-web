<?php
namespace App\Controller;

use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;

class SmsTwilioCertification
{
/**
* @Route("/login/success")
*/
public function NotifCertif(TexterInterface $texter)
{
$sms = new SmsMessage(
// the phone number to send the SMS message to
'+21690446128',
// the message
'Vous allez recevoir votre Certificat par mail 
        https://mail.google.com/mail'
);

$sentMessage = $texter->send($sms);

// ...
}
}