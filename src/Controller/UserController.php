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
        $listes = $utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')])->getListesOrderByMostRecent();
        $listeSansPremier = $listes->slice(1);
        $derniereListe = $listes[0];
        $listeDates = []; // tableau qui contiendra les dates de création de toutes les listes
        $i = 0;
        $listeListesRegroup = [];
        $listeListeDatePareille = [];
        
        foreach ($listes as $liste) {
            $temp = substr($liste->getDateCreation(), 0, 10);
            if (!in_array($temp, $listeDates)) {
                $listeDates[] = $temp;
            }
        }
      

        foreach ($listeDates as $date) {
            foreach ($listes as $liste) {
                $temp = substr($liste->getDateCreation(), 0, 10);
                if ($temp === $date) {
                    $listeListeDatePareille[] = $liste;
                }
            }
            $listeListesRegroup[$i] = $listeListeDatePareille;
            $listeListeDatePareille = [];
            $i++;
        }

        if($derniereListe){
            return $this->render('user/liste.html.twig', [
                'controller_name' => 'UserController',
                'listes' => $listeSansPremier,
                'pseudo' => $session->get('pseudo'),
                'derniereListe' => $derniereListe,
                'derniereListeId' => $derniereListe->getId(),
                'listeListesRegroup' => $listeListesRegroup,
                'listeDates' => $listeDates,
                'user' => $utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')]),
            ]);
        } else {
            return $this->render('user/liste.html.twig', [
                'controller_name' => 'UserController',
                'listes' => $listeSansPremier,
                'pseudo' => $session->get('pseudo'),
                'derniereListe' => null,
                'derniereListeId' => null,
                'listeListesRegroup' => null,
                'listeDates' => null,
                'user' => $utilisateurRepository->findOneBy(['pseudo' => $session->get('pseudo')]),
            ]);
        }
    }

    #[Route('/liste/{id}', name: 'app_user_liste_id')]
    public function listeID($id, SessionInterface $session, Request $request, ComposeRepository $composeRepository, ListeRepository $listeRepository): Response
    {
        $liste = $listeRepository->findOneBy(['id' => $id]);

        if (!$liste) {
            return $this->redirectToRoute('app_user_liste');
        }

        $userCreeListe = $liste->getCreePar()->first(); // récupère le premier utilisateur qui a créé la liste
        $currentUserPseudo = $session->get('pseudo'); // récupère le pseudo de l'utilisateur connecté

        $compose = new Compose();
        $coche = new Compose();
        $form = $this->createForm(AddArticleType::class, $compose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compose->setIdListe($liste);
            $composeRepository->save($compose, true);
        }

        for ($i = 0; $i < count($liste->getCreePar()); $i++) {
            if($liste->getCreePar()[$i]->getPseudo() === $currentUserPseudo) {
                return $this->render('user/show_liste.html.twig', [
                    'controller_name' => 'UserController',
                    'liste' => $liste,
                    'createur' => $userCreeListe,
                    'collaborateurs' => $liste->getCreePar(),
                    'form' => $form->createView()
                ]);
            }
        }

        return $this->redirectToRoute('app_user_liste');
    }

    
    #[Route('/liste/{id}/collaborer', name: 'app_user_liste_id_collaborer', methods: ['GET', 'POST'])]
    public function listeIDCollaborer(UtilisateurRepository $utilisateurRepository, ListeRepository $listeRepository, Liste $liste, Request $request): Response
    {
        $pseudo = $request->get('pseudo');

        $user = $utilisateurRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$user) {
            return $this->redirectToRoute('app_user_liste_id',
                ['id' => $liste->getId(),
                'error' => 'userNotFound'
            ],
                Response::HTTP_SEE_OTHER);
        }

        if ($liste->getCreePar()->contains($user)) {
            return $this->redirectToRoute('app_user_liste_id',
                ['id' => $liste->getId(),
                'error' => 'alreadyCollaborator'
            ],
                Response::HTTP_SEE_OTHER);
        }
        $liste->addCreePar($user);
     
        $listeRepository->save($liste, true);
        
        return $this->redirectToRoute('app_user_liste_id',
            ['id' => $liste->getId()],
            Response::HTTP_SEE_OTHER);
    }

    #[Route('/liste/{id}/supprimerCollab', name: 'app_user_liste_id_supprimer_collab', methods: ['GET', 'POST'])]
    public function listeIDSupprimerCollab(UtilisateurRepository $utilisateurRepository, ListeRepository $listeRepository, Liste $liste, Request $request): Response
    {
        $pseudo = $request->get('pseudo');

        $user = $utilisateurRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$user) {
            return $this->redirectToRoute('app_user_liste_id',
                ['id' => $liste->getId(),
                'error' => 'userNotFound'
            ],
                Response::HTTP_SEE_OTHER);
        }

        if (!$liste->getCreePar()->contains($user)) {
            return $this->redirectToRoute('app_user_liste_id',
                ['id' => $liste->getId(),
                'error' => 'notCollaborator'
            ],
                Response::HTTP_SEE_OTHER);
        }
        $liste->removeCreePar($user);
     
        $listeRepository->save($liste, true);
        
        return $this->redirectToRoute('app_user_liste_id',
            ['id' => $liste->getId()],
            Response::HTTP_SEE_OTHER);
    }


    #[Route('/liste/{id}/cocher', name: 'app_user_liste_id_cocher', methods: ['GET', 'POST'])]
    public function listeIDCocher(ComposeRepository $composeRepository, Liste $liste, Request $request): Response
    {
        $article = $request->get('idArticle');
        $listeQ = $request->get('idListe');
        $checkBox = $request->get('estMarque');
        $quantite = $request->get('quantite');
        if($checkBox == 'on'){
            $checkBox = 0;
        } else {
            $checkBox = 1;
        }

        $post = $composeRepository->findOneBy(['idListe' => $listeQ, 'idArticle' => $article, 'quantite' => $quantite]);

        if (!$composeRepository) {
            return $this->redirectToRoute('app_user_liste_id',
                ['id' => $liste->getId(),
                'error' => 'assoNotFound'
            ],
                Response::HTTP_SEE_OTHER);
        }

        $post->setEstMarque($checkBox);
     
        $composeRepository->save($post, true);
        
        return $this->redirectToRoute('app_user_liste_id',
            ['id' => $liste->getId()],
            Response::HTTP_SEE_OTHER);
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

            return $this->redirectToRoute('app_user_liste_articles', [
                'liste' => $liste,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new_liste.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/liste-articles', name: 'app_user_liste_articles', methods: ['GET', 'POST'])]
    public function listeArticles(ListeRepository $listeRepository,SessionInterface $session, ArticleRepository $articleRepository, Request $request, ComposeRepository $composeRepository): Response
    {
        $liste = $session->get("liste");
        $articles = $articleRepository->findAll();
        $liste = $listeRepository->findOneBy(['nomListe' => $liste]);

        $compose = new Compose();
        $form = $this->createForm(AddArticleType::class, $compose);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compose->setIdListe($liste);
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
