<?php
// src/Controller/MailerController.php
namespace App\Controller;

use http\Client\Curl\User;
use mPDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class CertificationMail extends AbstractController
{
#[Route('/email')]
public function sendEmail(MailerInterface $mailer): Response
{

$mpf =new Mpdf();
$session = new Session();

$content='<html>
    <head>
        <style type="text/css">
            body, html {
                margin: 0;
                padding: 0;
            }
            body {
                color: black;
                display: table;
                font-family: Georgia, serif;
                font-size: 24px;
                text-align: center;
            }
            .container {
                border: 20px solid tan;
                width: 750px;
                height: 563px;
                display: table-cell;
                vertical-align: middle;
            }
            .logo {
                color: tan;
            }

            .marquee {
                color: tan;
                font-size: 48px;
                margin: 20px;
            }
            .assignment {
                margin: 20px;
            }
            .person {
                border-bottom: 2px solid black;
                font-size: 32px;
                font-style: italic;
                margin: 20px auto;
                width: 400px;
            }
            .reason {
                margin: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                JOB.TN.COM
            </div>

            <div class="marquee">
                Certificat d Achèvement
            </div>

            <div class="assignment">
                Ce certificat est remis à 
            </div>

            <div class="person">
               '.$session->getName().'
            </div>

            <div class="reason">
                For deftly defying the laws of gravity<br/>
                and flying high
            </div>
        </div>
    </body>
</html>';
$mpf->writeHtml($content);
$mpf->setBodyBackgroundColor(2);
$contractNotePdf=$mpf->output('','S');

$email = (new Email())

->from('jardak.nader@esprit.tn')
    ->to('naderjardak5@gmail.com')
//->cc('cc@example.com')
//->bcc('bcc@example.com')
//->replyTo('fabien@example.com')
//->priority(Email::PRIORITY_HIGH)
->subject('JOB.TN.com')
->text('Felicitation !!')
->html('<p>See Twig integration for better HTML integration!</p>')
    ->attach($contractNotePdf,'certification.pdf');

$mailer->send($email);

return $this->render('quiz/success.html.twig', []);
// ...
}
}