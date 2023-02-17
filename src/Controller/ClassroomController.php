<?php

namespace App\Controller;

use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Classroom;

class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    #[Route('/afficher')]
    function affiche(ManagerRegistry $doctrine){
        $classroom=$doctrine->getRepository(Classroom::class)->FindAll();
        return $this->render('classroom/Affiche.html.twig',['cc'=>$classroom]);
    }
    #[Route('/afficher2')]
    function affiche2(ClassroomRepository $repo){
        $classroom=$repo->FindAll();
        return $this->render('classroom/Affiche.html.twig',['cc'=>$classroom]);
    }

    #[Route('/dd/{id}',name:'details')]
    function detail(ClassroomRepository $repo,$id)
    {
        $classroom=$repo->find($id);
        return $this->render('/classroom/detail.html.twig',['c'=>$classroom]);

    }


}
