<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UtilisateurRepository;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ListeRepository;
use App\Entity\Liste;
use App\Form\ListeType;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/liste', name: 'app_user_liste')]
    public function liste(UtilisateurRepository $utilisateurRepository, SessionInterface $session): Response
    {
        return $this->render('user/liste.html.twig', [
            'controller_name' => 'UserController',
            'listes' => $utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')])->getListes(),
        ]);
    }

    #[Route('/liste/{id}', name: 'app_user_liste_id')]
    public function listeID(): Response
    {
        return $this->render('user/liste.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/new-liste', name: 'app_user_liste_new', methods: ['GET', 'POST'])]
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

        return $this->renderForm('user/new_liste.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/liste-articles', name: 'app_user_listeArticles')]
    public function listeArticles(): Response
    {
        return $this->render('user/liste.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
