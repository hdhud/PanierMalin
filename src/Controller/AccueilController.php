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

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]

    public function index(Request $request, UtilisateurRepository $utilisateurRepository, SessionInterface $session): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($utilisateurRepository->findOneBy(['pseudo' => $utilisateur->getPseudo()])){
                $session->set("pseudo",$utilisateur->getPseudo());
                return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
            }
            $utilisateurRepository->save($utilisateur, true);

            $session->set("pseudo",$utilisateur->getPseudo());
            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }
}
