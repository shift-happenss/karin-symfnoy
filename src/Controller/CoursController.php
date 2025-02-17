<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cours')]
class CoursController extends AbstractController
{
    // Afficher la liste des cours
    #[Route('/', name: 'cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('chabeb/houssem/cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    // Créer un nouveau cours
    #[Route('/new', name: 'cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cours);
            $entityManager->flush();

            return $this->redirectToRoute('cours_index');
        }

        return $this->render('chabeb/houssem/cours/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Afficher les détails d'un cours
    #[Route('/{id}', name: 'cours_show', methods: ['GET'])]
    public function show(int $id, CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
        }

        return $this->render('chabeb/houssem/cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    // Modifier un cours
    #[Route('/{id}/edit', name: 'cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, CoursRepository $coursRepository, EntityManagerInterface $entityManager): Response
    {
        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Le cours à modifier n\'existe pas.');
        }

        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('cours_index');
        }

        return $this->render('chabeb/houssem/cours/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un cours
    #[Route('/{id}/delete', name: 'cours_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, CoursRepository $coursRepository, EntityManagerInterface $entityManager): Response
    {
        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Le cours à supprimer n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete' . $cours->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cours);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cours_index');
    }

    // Afficher la liste des cours pour les étudiants
    #[Route('/etudiant/cours', name: 'cours_show_etudiant', methods: ['GET'])]
    public function showEtudiant(CoursRepository $coursRepository): Response
    {
        return $this->render('chabeb/houssem/cours/show_etudiant.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }
}