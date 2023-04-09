<?php



namespace App\Controller;



use App\Entity\Badge;
use App\Entity\CertifBadge;
use App\Entity\QuestionReponse;
use App\Form\QuiztestType;
use App\Repository\BadgeRepository;
use App\Repository\CertifBadgeRepository;
use App\Repository\CertificationRepository;
use App\Repository\QuestionReponseRepository;
use App\Form\QuestionReponseType;
use App\Repository\QuizRepository;
use App\Repository\UtilisateurRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Session\Session;
use mPDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\TexterInterface;
use Sasedev\MpdfBundle\Factory\MpdfFactory;


#[Route('/question/reponse')]
class QuestionReponseController extends AbstractController
{



    #[Route('/{id}/testQuiz', name: 'app_quiz_test', methods: ['GET', 'POST'])]
    public function indextest(Session $s,UtilisateurRepository $utilisateurRepository,Request $rq,SmsTwilioCertification $stc , TexterInterface $texter,MailerInterface $sm,QuizRepository $qr, Request $request, QuestionReponseRepository $qrr, $id, CertificationRepository $cerr, BadgeRepository $br, CertifBadgeRepository $cbr , UtilisateurRepository $ur,FlashyNotifier $flashy): Response
    {

        $certification = $cerr->find($id);
        $question = $qrr->findBy(['idQuiz' => $certification->getIdQuiz()], null, null, null);
        $form = $this->createForm(QuiztestType::class, null);

        if (! $rq->isMethod('POST')) {

            $s->set('k', random_int(0, 3));
            $s->set('l', random_int(0, 3));
            $s->set('n', random_int(0, 3));
            $s->set('f', random_int(0, 3));
        }
            $k=$s->get('k');
            $l=$s->get('l');
            $f=$s->get('f');
            $n=$s->get('s');

        $Q1_1 = [$question[0]->getReponseF1() => 0];
        $Q1_2 = [$question[0]->getReponseF2() => 0];
        $Q1_3 = [$question[0]->getReponseV() => 1];

        if ($k == 1) {
            $Q1 = [$Q1_3, $Q1_1, $Q1_2];
        } elseif ($k == 2) {
            $Q1 = [$Q1_1, $Q1_3, $Q1_2];
        } else
            $Q1 = [$Q1_1, $Q1_2, $Q1_3];


        $Q2_1 = [$question[1]->getReponseF1() => 0];
        $Q2_2 = [$question[1]->getReponseF2() => 0];
        $Q2_3 = [$question[1]->getReponseV() => 1];


        if ($l == 1) {
            $Q2 = [$Q2_3, $Q2_1, $Q2_2];
        } elseif ($l == 2) {
            $Q2 = [$Q2_1, $Q2_3, $Q2_2];
        } else
            $Q2 = [$Q2_1, $Q2_2, $Q2_3];


        $Q3_1 = [$question[2]->getReponseF1() => 0];
        $Q3_2 = [$question[2]->getReponseF2() => 0];
        $Q3_3 = [$question[2]->getReponseV() => 1];

        if ($n == 1) {
            $Q3 = [$Q3_3, $Q3_1, $Q3_2];
        } elseif ($n == 2) {
            $Q3 = [$Q3_1, $Q3_3, $Q3_2];
        } else
            $Q3 = [$Q3_1, $Q3_2, $Q3_3];


        $Q4_1 = [$question[3]->getReponseF1() => 0];
        $Q4_2 = [$question[3]->getReponseF2() => 0];
        $Q4_3 = [$question[3]->getReponseV() => 1];

        if ($f == 1) {
            $Q4 = [$Q4_3, $Q4_1, $Q4_2];
        } elseif ($f == 2) {
            $Q4 = [$Q4_1, $Q4_3, $Q4_2];
        } else
            $Q4 = [$Q4_1, $Q4_2, $Q4_3];


        $form->add('question1', ChoiceType::class, [
            'label' => $question[0]->getQuestion(),
            'choices' => $Q1,
            'expanded' => true,])
            ->add("question2", ChoiceType::class, [
                'label' => $question[1]->getQuestion(),
                'choices' => $Q2,
                'expanded' => true,])
            ->add("question3", ChoiceType::class, [
                'label' => $question[2]->getQuestion(),
                'choices' => $Q3,
                'expanded' => true,])
            ->add("question4", ChoiceType::class, [
                'label' => $question[3]->getQuestion(),
                'choices' => $Q4,
                'expanded' => true,]);


        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $rq1 = $form['question1']->getData();
            $rq2 = $form['question2']->getData();
            $rq3 = $form['question3']->getData();
            $rq4 = $form['question4']->getData();
            $res = $rq1 + $rq2 + $rq3 + $rq4;
            if ($res > 2) {
                $session = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

                $badge = new Badge();
                $badge->setNom($certification->getTitrecours());
                $br->save($badge, true);
                $allB = $br->findAll();
                $r = $allB[count($allB) - 1];
                $cb = new CertifBadge();
                $u = $ur->find($session->getId());
                $cb->setIdCertif($certification);
                $cb->setIdBadge($r);
                $cb->setIdUser($u);
                $cbr->save($cb, true);


                #sms commented 3al flous :p /////////---------TWILIO--------/////////////


                #el mail bil sessionMANAGER
                $email = (new Email())->from('jardak.nader@esprit.tn')
                    ->to('naderjardak5@gmail.com')
                    ->subject('JOB.TN.com')
                    ->text('Felicitation !!')
                    ->html('<html>
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
               '. $session->getNom() .'
            </div>

            <div class="reason">
                For deftly defying the laws of gravity<br/>
                and flying high
            </div>
        </div>
    </body>
</html>');

                $sm->send($email);

                //$stc->NotifCertif($texter);
                return $this->redirectToRoute('app_certification_indexu', ['r'=>2]);
            }
            else {
                return $this->redirectToRoute('app_certification_indexu', ['r'=>3]);            }

        }

        return $this->render('quiz/quiz_test.html.twig', [
            'form'=>$form->createView(),
        ]);
    }



    #[Route('/{id}/save', name: 'app_question_reponse_index_save', methods: ['GET'])]
    public function index_save(QuestionReponseRepository $questionReponseRepository,QuizRepository $quiz_r,$id): Response
    {
        $quiz=$quiz_r->findBy(array('id'=>$id),null,null,null);
        $ques=$questionReponseRepository->findBy(array('idQuiz'=>$quiz),null,null,null);
        return $this->render('quiz/questions.html.twig', [
            'ques' => $ques,
        ]);

    }
    #[Route('/deleted/{id}', name: 'app_question_reponse_index_deleted', methods: ['GET'])]
    public function index_deleted(QuestionReponseRepository $questionReponseRepository,QuizRepository $quiz_r,$id): Response
    {
        $quiz=$quiz_r->findBy(array('id'=>$id),null,null,null);
        $ques=$questionReponseRepository->findBy(array('idQuiz'=>$quiz),null,null,null);
        return $this->render('quiz/questions.html.twig', [
            'ques' => $ques,
        ]);

    }
    #[Route('/{id}', name: 'app_question_reponse_index', methods: ['GET'])]
    public function index(QuestionReponseRepository $questionReponseRepository,QuizRepository $quiz_r,$id): Response
    {   $ques1=$questionReponseRepository->find($id);
        $id_q=$ques1->getIdQuiz()->getId();
        $quiz=$quiz_r->findBy(array('id'=>$id_q),null,null,null);
        $ques=$questionReponseRepository->findBy(array('idQuiz'=>$quiz),null,null,null);
        return $this->render('quiz/questions.html.twig', [
            'ques' => $ques,
        ]);

    }



    #[Route('/{id}/new', name: 'app_question_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuestionReponseRepository $questionReponseRepository,QuizRepository $qr,$id): Response
    {

        $questionReponse = new QuestionReponse();
        $form = $this->createForm(QuestionReponseType::class, $questionReponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $quiz=$qr->find($id);
            $qq=$questionReponseRepository->findBy(['idQuiz'=>$quiz],null,null,null);
            if(count($qq)<4)
            {
                $questionReponse->setIdQuiz($quiz);
                $questionReponseRepository->save($questionReponse, true);
                return $this->redirectToRoute('app_question_reponse_index_save',['id'=>$id], Response::HTTP_SEE_OTHER);

            }

        }

        return $this->renderForm('question_reponse/new.html.twig', [
            'question_reponse' => $questionReponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_question_reponse_show', methods: ['GET'])]
    public function show(QuestionReponse $questionReponse): Response
    {
        return $this->render('question_reponse/show.html.twig', [
            'question_reponse' => $questionReponse,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_question_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuestionReponse $questionReponse, QuestionReponseRepository $questionReponseRepository,$id): Response
    {
        $form = $this->createForm(QuestionReponseType::class, $questionReponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionReponseRepository->save($questionReponse, true);
            return $this->redirectToRoute('app_question_reponse_index', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question_reponse/edit.html.twig', [
            'question_reponse' => $questionReponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_question_reponse_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, QuestionReponse $questionReponse, QuestionReponseRepository $questionReponseRepository,$id): Response
    {
        $qr=$questionReponseRepository->find($id);
        $quiz=$qr->getIdQuiz()->getId();
        if ($this->isCsrfTokenValid('delete'.$questionReponse->getId(), $request->request->get('_token'))) {
            $questionReponseRepository->remove($questionReponse, true);
        }
        return $this->redirectToRoute('app_question_reponse_index_deleted', ['id'=>$quiz], Response::HTTP_SEE_OTHER);
    }
}
