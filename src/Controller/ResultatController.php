<?php

// src/Controller/ResultatController.php
namespace App\Controller;

use App\Entity\Examen;
use App\Repository\ExamenRepository;
use App\Repository\ReponseEtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/resultats')]
class ResultatController extends AbstractController
{
    // Afficher la liste des examens passés par l'étudiant
    #[Route('/', name: 'resultat_index', methods: ['GET'])]
    public function index(ExamenRepository $examenRepository): Response
    {
        // Récupérer les examens passés par l'étudiant connecté
        $examens = $examenRepository->findBy(['utilisateur' => $this->getUser()]);

        return $this->render('chabeb/houssem/resultat/index.html.twig', [
            'examens' => $examens,
        ]);
    }

    // Afficher les détails d'un examen et les réponses de l'étudiant
    #[Route('/{id}', name: 'resultat_show', methods: ['GET'])]
    public function show(Examen $examen, ReponseEtudiantRepository $reponseEtudiantRepository): Response
    {
        // Récupérer les réponses de l’étudiant associées à cet examen
        $reponsesEtudiant = $reponseEtudiantRepository->findBy(['reponse_id' => $examen]);

        return $this->render('chabeb/houssem/resultat/show.html.twig', [
            'examen' => $examen,
            'reponsesEtudiant' => $reponsesEtudiant,
        ]);
    }
}
