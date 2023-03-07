<?php

namespace App\Controller;

use App\Entity\Compose;
use App\Form\ComposeType;
use App\Repository\ComposeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compose')]
class ComposeController extends AbstractController
{
    #[Route('/', name: 'app_compose_index', methods: ['GET'])]
    public function index(ComposeRepository $composeRepository): Response
    {
        return $this->render('compose/index.html.twig', [
            'composes' => $composeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_compose_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ComposeRepository $composeRepository): Response
    {
        $compose = new Compose();
        $form = $this->createForm(ComposeType::class, $compose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $composeRepository->save($compose, true);

            return $this->redirectToRoute('app_compose_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compose/new.html.twig', [
            'compose' => $compose,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compose_show', methods: ['GET'])]
    public function show(Compose $compose): Response
    {
        return $this->render('compose/show.html.twig', [
            'compose' => $compose,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_compose_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Compose $compose, ComposeRepository $composeRepository): Response
    {
        $form = $this->createForm(ComposeType::class, $compose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $composeRepository->save($compose, true);

            return $this->redirectToRoute('app_compose_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('compose/edit.html.twig', [
            'compose' => $compose,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_compose_delete', methods: ['POST'])]
    public function delete(Request $request, Compose $compose, ComposeRepository $composeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compose->getId(), $request->request->get('_token'))) {
            $composeRepository->remove($compose, true);
        }

        return $this->redirectToRoute('app_compose_index', [], Response::HTTP_SEE_OTHER);
    }
}
