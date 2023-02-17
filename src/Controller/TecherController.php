<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TecherController extends AbstractController
{ 
   #[Route('/techer', name: 'app_techer')]
    public function index(): Response
    {
        return $this->render('techer/index.html.twig', [
            'controller_name' => 'TecherController',
        ]);
    }
    
    #[Route('/test/{classe}')]
    function test($classe){
        return $this->render('techer/test.html.twig',['c'=>$classe]);
    }
    #[Route('/ListeF')]
    function ListeF()
    {
        $formations = array(
            array('ref' => 'form147', 'Titre' => 'Formation Symfony 4', 'Description' => 'formation pratique',
                'date_debut' => '12/06/2020', 'date_fin' => '19/06/2020', 'nb_participants' => 0),
            array('ref' => 'form177', 'Titre' => 'Formation SOA',
                'Description' => 'formation theorique', 'date_debut' => '03/12/2020', 'date_fin' => '10/12/2020',
                'nb_participants' => 0),
            array('ref' => 'form178', 'Titre' => 'Formation Angular',
                'Description' => 'formation theorique', 'date_debut' => '10/06/2020', 'date_fin' => '14/06/2020',
                'nb_participants' => 12));
        return $this->render(
            'ListeF.html.twig',
            ['ff' => $formations]
        );

    }


    #[Route('/detail/{titre}',name:'detail')]
    function detail($titre)
    {
        return $this->render('techer/detail.html.twig',['t'=>$titre]);

    }


}
