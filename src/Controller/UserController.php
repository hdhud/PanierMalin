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
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Entity\Compose;
use App\Form\ArticleType;
use App\Form\ComposeType;
use App\Repository\ComposeRepository;
use App\Form\AddArticleType;

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
        $listes = $utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')])->getListes();
        $listesDates = [];
        $derniereListe = $listes[0];
        for($i = 0; $i < count($listes); $i++){
            if ($listes[$i]->getDateCreation() > $derniereListe->getDateCreation()){
                $derniereListe = $listes[$i];

            }
        }

        return $this->render('user/liste.html.twig', [
            'controller_name' => 'UserController',
            'listes' => $listes,
            'pseudo' => $session->get('pseudo'),
            'derniereListe' => $derniereListe,
            'derniereListeId' => $derniereListe->getId(),
            'listeDatesMieux' => $listesDates,
        ]);
    }

    #[Route('/liste/{id}', name: 'app_user_liste_id')]
    public function listeID(Liste $liste, SessionInterface $session, Request $request, ComposeRepository $composeRepository): Response
    {
        $userCreeListe = $liste->getCreePar()->first(); // récupère le premier utilisateur qui a créé la liste
        $currentUserPseudo = $session->get('pseudo'); // récupère le pseudo de l'utilisateur connecté

        $compose = new Compose();
        $form = $this->createForm(AddArticleType::class, $compose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compose->setIdListe($liste);
            $composeRepository->save($compose, true);
        }
    
        if ($userCreeListe && $userCreeListe->getPseudo() === $currentUserPseudo) {
            return $this->render('user/show_liste.html.twig', [
                'controller_name' => 'UserController',
                'liste' => $liste,
                'pseudo' => $session->get('pseudo'),
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_user_liste');
        }
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

            return $this->redirectToRoute('app_user_liste_articles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new_liste.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/liste-articles', name: 'app_user_liste_articles', methods: ['GET', 'POST'])]
    public function listeArticles(SessionInterface $session, ArticleRepository $articleRepository, Request $request, ComposeRepository $composeRepository): Response
    {
        $liste = $session->get("liste");
        $articles = $articleRepository->findAll();

        $compose = new Compose();
        $form = $this->createForm(ComposeType::class, $compose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $composeRepository->save($compose, true);
        }

        return $this->render('user/liste_articles.html.twig', [
            'controller_name' => 'UserController', 
            "liste" => $liste,
            "articles" => $articles,
            "form" => $form->createView(),
        ]);
    }
}
