<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(OffreRepository $offreRepository): Response
    {//tets
        $u=$offreRepository->findAll();
        return $this->render('utilisateur/test.html.twig', [
            'controller_name' => 'AccueilController',
            'o'=>$u
        ]);
    }
}
