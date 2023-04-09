<?php

namespace App\Controller;
use App\Entity\Offre;
use App\Entity\Candidature;
use App\Repository\OffreRepositoryRepository;
use App\Repository\CandidatureRepository;
use App\Form\CandidatureType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
//fonction affiche liste tt canditure admin
    #[Route('/admin/candidat/', name: 'app_candidature_dashboard', methods: ['GET'])]
    public function dashboardcandidat(CandidatureRepository $candidatureRepository): Response
    {

        return $this->render('utilisateur/Dashbord/candidature/index.html.twig', [
            'nonTraitees' => $candidatureRepository->findBy(["statut" => 'non traité']),
            'acceptees' => $candidatureRepository->findBy(["statut" => 'acceptée']),
            'nonAcceptees' => $candidatureRepository->findBy(["statut" => 'refusée'])
        ]);
    }
//fonction affiche liste condidature client
    #[Route('/', name: 'app_candidature_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository,CandidatureRepository $candidatureRepository): Response
    {        $candidat = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatureRepository->findby(['idcondidat'=>$candidat->getId()]),
        ]);
    }
//fonction creation nv candidature
    #[Route('/new/{id}', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function new(Offre $offre, Request $request, CandidatureRepository $candidatureRepository, UtilisateurRepository $utilisateurRepository): Response
    {        $candidat = $utilisateurRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $candidature->setIdOffre($offre);
            $candidature->setIdcondidat($candidat);
            $candidature->setStatut("non traité");
            $candidature->setTitre($offre->getTitre());
            $candidature->setDateexpiration($offre->getDateexpiration());
            $candidature->setDateajout(date('Y-m-d'));
            $candidatureRepository->save($candidature, true);

            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidature/new.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }

    #[Route('/adminCondidat/{id}', name: 'app_candidature_show_admin', methods: ['GET'])]
    public function showAdmin(Candidature $candidature): Response
    {
        return $this->render('utilisateur/Dashbord/candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }
    #[Route('/{id}', name: 'app_candidature_show', methods: ['GET'])]
    public function show(Candidature $candidature): Response
    {
        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_candidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidature $candidature, CandidatureRepository $candidatureRepository): Response
    {
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidatureRepository->save($candidature, true);

            return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    public function delete(Request $request, Candidature $candidature, CandidatureRepository $candidatureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            $candidatureRepository->remove($candidature, true);
        }

        return $this->redirectToRoute('app_candidature_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('refuserCandidature/{id}', name: 'refuser_candidature', methods: ['GET'])]
    public function refuserCandidature(int $id, CandidatureRepository $candidatureRepository): Response
    {
        $candidature = $candidatureRepository->findOneBy(["id" => $id]);
        $candidature->setStatut("refusée");
        $candidatureRepository->save($candidature, true);
        return $this->render('utilisateur/Dashbord/candidature/index.html.twig', [
            'nonTraitees' => $candidatureRepository->findBy(["statut" => 'non traité']),
            'acceptees' => $candidatureRepository->findBy(["statut" => 'acceptée']),
            'nonAcceptees' => $candidatureRepository->findBy(["statut" => 'refusée'])
        ]);
    }


    #[Route('/getAssistance/{id}', name: 'assistance', methods: ['GET'])]
    public function assistance(int $id, HubInterface $publisher): Response
    {
        // 2éme etape connexion au chat
        $update = new Update("http://127.0.0.1:8000/assistans", $id);
        $publisher->publish($update);
        return new Response("true", 200);
    }

    #[Route('/sendMessage/{id}', name: 'sendMessage', methods: ['POST'])]
    public function sendMessage(int $id, Request $request, HubInterface $publisher, UtilisateurRepository $utilisateurRepository): Response
    {
        if($request->get("senderId") == -1) {

            $user = $utilisateurRepository->findOneBy(["id"=>$id]);
            $update = new Update("http://127.0.0.1:8000/chat/".$id, json_encode(['sender' => $request->get("senderId"), 'message' =>$user->getNom()." ".$user->getPrenom() , 'senderName' => $request->get("senderName")]));

        } else {
            $update = new Update("http://127.0.0.1:8000/chat/".$id, json_encode(['sender' => $request->get("senderId"), 'message' => $request->get("message"), 'senderName' => $request->get("senderName")]));
        }
        $publisher->publish($update);
        return new Response("true", 200);
    }
}
