<?php

namespace App\Controller;

use App\Entity\TypeArticle;
use App\Form\TypeArticleType;
use App\Repository\TypeArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/article')]
class TypeArticleController extends AbstractController
{
    #[Route('/', name: 'app_type_article_index', methods: ['GET'])]
    public function index(TypeArticleRepository $typeArticleRepository): Response
    {
        return $this->render('type_article/index.html.twig', [
            'type_articles' => $typeArticleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeArticleRepository $typeArticleRepository): Response
    {
        $typeArticle = new TypeArticle();
        $form = $this->createForm(TypeArticleType::class, $typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeArticleRepository->save($typeArticle, true);

            return $this->redirectToRoute('app_type_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_article/new.html.twig', [
            'type_article' => $typeArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_article_show', methods: ['GET'])]
    public function show(TypeArticle $typeArticle): Response
    {
        return $this->render('type_article/show.html.twig', [
            'type_article' => $typeArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeArticle $typeArticle, TypeArticleRepository $typeArticleRepository): Response
    {
        $form = $this->createForm(TypeArticleType::class, $typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeArticleRepository->save($typeArticle, true);

            return $this->redirectToRoute('app_type_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_article/edit.html.twig', [
            'type_article' => $typeArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_article_delete', methods: ['POST'])]
    public function delete(Request $request, TypeArticle $typeArticle, TypeArticleRepository $typeArticleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeArticle->getId(), $request->request->get('_token'))) {
            $typeArticleRepository->remove($typeArticle, true);
        }

        return $this->redirectToRoute('app_type_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
