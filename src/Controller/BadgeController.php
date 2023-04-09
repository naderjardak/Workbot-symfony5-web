<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Entity\CertifBadge;
use App\Repository\BadgeRepository;
use App\Form\BadgeType;
use App\Repository\CertifBadgeRepository;
use App\Repository\OffreRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/badge')]
class
BadgeController extends AbstractController
{
    #[Route('/', name: 'app_badge_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository,BadgeRepository $badgeRepository,OffreRepository $ofr): Response
    {
        $session = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $nombre=count($ofr->findAll());
        return $this->render('badge/index.html.twig', [
            'badges' => $badgeRepository->Afficher_certif_badge($session->getId()),
            'nb'=>$nombre,
        ]);
    }

    #[Route('/new', name: 'app_badge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BadgeRepository $badgeRepository): Response
    {
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $badgeRepository->save($badge, true);

            return $this->redirectToRoute('app_badge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/new.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_badge_show', methods: ['GET'])]
    public function show(Badge $badge): Response
    {
        return $this->render('badge/show.html.twig', [
            'badge' => $badge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_badge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Badge $badge, BadgeRepository $badgeRepository): Response
    {
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $badgeRepository->save($badge, true);

            return $this->redirectToRoute('app_badge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('badge/edit.html.twig', [
            'badge' => $badge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_badge_delete', methods: ['POST'])]
    public function delete(Request $request, Badge $badge, BadgeRepository $badgeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$badge->getId(), $request->request->get('_token'))) {
            $badgeRepository->remove($badge, true);
        }

        return $this->redirectToRoute('app_badge_index', [], Response::HTTP_SEE_OTHER);
    }
}
