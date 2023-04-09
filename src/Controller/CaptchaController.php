<?php

namespace App\Controller;

use App\Entity\Captcha;
use App\Repository\CaptchaRepository;
use App\Form\CaptchaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/captcha')]
class CaptchaController extends AbstractController
{
    #[Route('/', name: 'app_captcha_index', methods: ['GET'])]
    public function index(CaptchaRepository $captchaRepository): Response
    {
        return $this->render('captcha/index.html.twig', [
            'captchas' => $captchaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_captcha_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CaptchaRepository $captchaRepository): Response
    {
        $captcha = new Captcha();
        $form = $this->createForm(CaptchaType::class, $captcha);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $captchaRepository->save($captcha, true);

            return $this->redirectToRoute('app_captcha_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('captcha/new.html.twig', [
            'captcha' => $captcha,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_captcha_show', methods: ['GET'])]
    public function show(Captcha $captcha): Response
    {
        return $this->render('captcha/show.html.twig', [
            'captcha' => $captcha,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_captcha_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Captcha $captcha, CaptchaRepository $captchaRepository): Response
    {
        $form = $this->createForm(CaptchaType::class, $captcha);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $captchaRepository->save($captcha, true);

            return $this->redirectToRoute('app_captcha_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('captcha/edit.html.twig', [
            'captcha' => $captcha,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_captcha_delete', methods: ['POST'])]
    public function delete(Request $request, Captcha $captcha, CaptchaRepository $captchaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$captcha->getId(), $request->request->get('_token'))) {
            $captchaRepository->remove($captcha, true);
        }

        return $this->redirectToRoute('app_captcha_index', [], Response::HTTP_SEE_OTHER);
    }
}
