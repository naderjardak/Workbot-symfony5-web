<?php

namespace App\Controller;
use App\Entity\Offre;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AffichageoffreController extends AbstractController
{
    #[Route('/affichageoffre', name: 'app_affichageoffre')]
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('affichageoffre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }
    #[Route('appel/{id}', name: 'entrer', methods: ['GET'])]
    public function show (Offre $offre): Response
    {
        return $this->render('affichageoffre/detailoffre.html.twig', [
            'offre' => $offre,
        ]);
    }


}


