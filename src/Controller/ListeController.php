<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\UtilisateurRepository;

#[Route('/liste')]
class ListeController extends AbstractController
{
    #[Route('/', name: 'app_liste_index', methods: ['GET'])]
    public function index(ListeRepository $listeRepository,UtilisateurRepository $utilisateurRepository ,SessionInterface $session): Response
    {
        $session->get('pseudo');
        return $this->render('liste/index.html.twig', [
            'listes' => $utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')])->getListes(),
        ]);
    }

    #[Route('/new', name: 'app_liste_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UtilisateurRepository $utilisateurRepository, ListeRepository $listeRepository, SessionInterface $session): Response
    {
        $liste = new Liste();
        $liste->addCreePar($utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')]));
        $liste->setDateCreation((new \DateTime())->format("Y-m-d H:i:s"));
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listeRepository->save($liste, true);
            $session->set("liste",$liste->getNomListe());

            return $this->redirectToRoute('app_user_listeArticles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('liste/new.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_show', methods: ['GET'])]
    public function show(Liste $liste): Response
    {
        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Liste $liste, ListeRepository $listeRepository): Response
    {
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listeRepository->save($liste, true);

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('liste/edit.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_delete', methods: ['POST'])]
    public function delete(Request $request, Liste $liste, ListeRepository $listeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liste->getId(), $request->request->get('_token'))) {
            $listeRepository->deleteListeAndComposes($liste);
        }

        return $this->redirectToRoute('app_user_liste', [], Response::HTTP_SEE_OTHER);
    }
}
