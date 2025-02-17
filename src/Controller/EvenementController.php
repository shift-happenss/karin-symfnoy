<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud_evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $type = $request->query->get('type');
        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('dateFin');

        $query = $evenementRepository->findAllWithPaginationAndFilter($request->query->getInt('page', 1), 5, $type, $dateDebut, $dateFin);

        $evenements = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('chabeb/ahmed/evenement/index.html.twig', [
            'evenements' => $evenements,
            'type' => $type,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);
    }  

    #[Route('/new', name: 'evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            $this->addFlash('success', 'Événement ajouté avec succès.');

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('chabeb/ahmed/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('chabeb/ahmed/evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    #[Route('/{id}/edit', name: 'evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Événement modifié avec succès.');

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('chabeb/ahmed/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'Événement supprimé avec succès.');
        }

        return $this->redirectToRoute('evenement_index');
    }
// Afficher la liste des event pour les étudiants
#[Route('/etudiant/evenements', name: 'event_show_etudiant', methods: ['GET'])]
public function showEtudiant(EvenementRepository $evenementRepository): Response
{
    return $this->render('chabeb/ahmed/evenement/show_etudiant.html.twig', [
        'evenements' => $evenementRepository->findAll(),
    ]);
}
// Afficher la liste des event pour les parent
#[Route('/parent/evenements', name: 'event_show_parent', methods: ['GET'])]
public function showParent(EvenementRepository $evenementRepository): Response
{
    return $this->render('chabeb/ahmed/evenement/show_parent.html.twig', [
        'evenements' => $evenementRepository->findAll(),
    ]);
}
// Afficher la liste des event pour les parent
#[Route('/enseignant/evenements', name: 'event_show_teacher', methods: ['GET'])]
public function showTeacher(EvenementRepository $evenementRepository): Response
{
    return $this->render('chabeb/ahmed/evenement/show_prof.html.twig', [
        'evenements' => $evenementRepository->findAll(),
    ]);
}
}
