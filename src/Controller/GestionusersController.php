<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface; // Assure-toi d'utiliser cette classe ici
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GestionusersController extends AbstractController
{
    #[Route('/gestionusers', name: 'gestionusers')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        // Passer les utilisateurs à la vue
        return $this->render('back/gestionusers/index.html.twig', [
            'users' => $users, // On passe la variable users à Twig
        ]);
    }
}