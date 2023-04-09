<?php
namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Home')]
class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('utilisateur/client/client.html.twig', [
        ]);
    }
}