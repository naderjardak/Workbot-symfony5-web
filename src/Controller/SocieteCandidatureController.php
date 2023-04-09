<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SocieteCandidatureController extends AbstractController
{


    #[Route('/societe/candidature', name: 'app_societe_candidature', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository): Response
    {
        return $this->render('societe_candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findAll(),
        ]);
    }

}
