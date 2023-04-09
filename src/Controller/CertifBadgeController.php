<?php

namespace App\Controller;

use App\Entity\CertifBadge;
use App\Repository\CertifBadgeRepository;
use App\Form\CertifBadgeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/certif/badge')]
class CertifBadgeController extends AbstractController
{
    #[Route('/', name: 'app_certif_badge_index', methods: ['GET'])]
    public function index(CertifBadgeRepository $certifBadgeRepository): Response
    {
        return $this->render('certif_badge/index.html.twig', [
            'certif_badges' => $certifBadgeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_certif_badge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CertifBadgeRepository $certifBadgeRepository): Response
    {
        $certifBadge = new CertifBadge();
        $form = $this->createForm(CertifBadgeType::class, $certifBadge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $certifBadgeRepository->save($certifBadge, true);

            return $this->redirectToRoute('app_certif_badge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certif_badge/new.html.twig', [
            'certif_badge' => $certifBadge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_certif_badge_show', methods: ['GET'])]
    public function show(CertifBadge $certifBadge): Response
    {
        return $this->render('certif_badge/show.html.twig', [
            'certif_badge' => $certifBadge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_certif_badge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CertifBadge $certifBadge, CertifBadgeRepository $certifBadgeRepository): Response
    {
        $form = $this->createForm(CertifBadgeType::class, $certifBadge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $certifBadgeRepository->save($certifBadge, true);

            return $this->redirectToRoute('app_certif_badge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('certif_badge/edit.html.twig', [
            'certif_badge' => $certifBadge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_certif_badge_delete', methods: ['POST'])]
    public function delete(Request $request, CertifBadge $certifBadge, CertifBadgeRepository $certifBadgeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$certifBadge->getId(), $request->request->get('_token'))) {
            $certifBadgeRepository->remove($certifBadge, true);
        }

        return $this->redirectToRoute('app_certif_badge_index', [], Response::HTTP_SEE_OTHER);
    }
}
