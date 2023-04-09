<?php

namespace App\Controller;


use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Repository\EvennementRepository;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/sponsor')]
class SponsorController extends AbstractController
{
    #[Route('/', name: 'app_sponsor_index', methods: ['GET'])]
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('sponsor/index.html.twig', [
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_sponsor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SponsorRepository $sponsorRepository, $id, EvennementRepository $eventrepo, SluggerInterface $slugger): Response
    {
        $sponsor = new Sponsor();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);
        $event = $eventrepo->find($id);
        $sponsor->setIdEvenement($event);

        if ($form->isSubmitted() && $form->isValid()) {
            $logophot = $form->get('logo')->getData();
            if ($logophot) {
                $originalFilename = pathinfo($logophot->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logophot->guessExtension();

                try {
                    $logophot->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $exception) {

                }

                $sponsor->setLogo($newFilename);
            }

            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('sponsh', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/new.html.twig', [
            'sponsor' => $sponsor,
            'evsp' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_show', methods: ['GET'])]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sponsor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $logophot = $form->get('logo')->getData();
            if ($logophot) {
                $originalFilename = pathinfo($logophot->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logophot->guessExtension();

                try {
                    $logophot->move(
                        $this->getParameter('logo_directory'),
                        $newFilename
                    );
                } catch (FileException $exception) {

                }

                $sponsor->setLogo($newFilename);
            }

            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('app_evennement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository, $id): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sponsor->getId(), $request->request->get('_token'))) {
            $sponsorRepository->remove($sponsor, true);
        }

        return $this->redirectToRoute('sponsh', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
