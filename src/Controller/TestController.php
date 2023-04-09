<?php

namespace App\Controller;

use App\Entity\Test;
use App\Repository\TestRepository;
use App\Form\TestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/', name: 'app_test_index', methods: ['GET'])]
    public function index(TestRepository $testRepository): Response
    {
        return $this->render('test/index.html.twig', [
            'tests' => $testRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TestRepository $testRepository): Response
    {
        $test = new Test();
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testRepository->save($test, true);

            return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('test/new.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_test_show', methods: ['GET'])]
    public function show(Test $test): Response
    {
        return $this->render('test/show.html.twig', [
            'test' => $test,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_test_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Test $test, TestRepository $testRepository): Response
    {
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testRepository->save($test, true);

            return $this->redirectToRoute('app_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('test/edit.html.twig', [
            'test' => $test,
            'form' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_test_delete')]
    public function delete(Request $request, Test $test, TestRepository $testRepository): Response
    {
            $testRepository->remove($test, true);
        $this->addFlash('deleteT', 'Test supprimé avec succées');




        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
