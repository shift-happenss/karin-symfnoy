<?php

namespace App\Controller;

use App\Entity\Emploi;
use App\Form\EmploiType;
use App\Repository\EmploiRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud_emploi')]
class EmploiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EmploiRepository $emploiRepository;
    
    public function __construct(EntityManagerInterface $entityManager, EmploiRepository $emploiRepository)
    {
        $this->entityManager = $entityManager;
        $this->emploiRepository = $emploiRepository;
    }

    #[Route('/', name: 'emploi_index', methods: ['GET'])]
    public function index(Request $request): Response
    {   // Récupérer les valeurs des paramètres GET
        $titre = $request->query->get('titre', '');
        $proprietaire = $request->query->get('proprietaire', '');
        $emplois = $this->emploiRepository->findAll();
        
        return $this->render('chabeb/ahmed/emploi/index.html.twig', [
            'emplois' => $emplois,
            'titre' => $titre,  // Pour pré-remplir le formulaire
            'proprietaire' => $proprietaire
        ]);
    }

    #[Route('/new', name: 'emploi_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $emploi = new Emploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($emploi);
            $this->entityManager->flush();

            $this->addFlash('success', 'Emploi créé avec succès.');

            return $this->redirectToRoute('emploi_index');
        }

        return $this->render('chabeb/ahmed/emploi/new.html.twig', [
            'emploi' => $emploi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'emploi_show', methods: ['GET'])]
    public function show(Emploi $emploi): Response
    {
        return $this->render('chabeb/ahmed/emploi/show.html.twig', [
            'emploi' => $emploi,
        ]);
    }

    #[Route('/{id}/edit', name: 'emploi_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Emploi $emploi): Response
    {
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Emploi mis à jour avec succès.');

            return $this->redirectToRoute('emploi_index');
        }

        return $this->render('chabeb/ahmed/emploi/edit.html.twig', [
            'emploi' => $emploi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'emploi_delete', methods: ['POST'])]
    public function delete(Request $request, Emploi $emploi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emploi->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($emploi);
            $this->entityManager->flush();
            $this->addFlash('success', 'Emploi supprimé avec succès.');
        }

        return $this->redirectToRoute('emploi_index');
    }
    // Afficher la liste des event pour les étudiants
#[Route('/etudiant/emplois', name: 'emploi_show_etudiant', methods: ['GET'])]
public function showEtudiant(EmploiRepository $emploiRepository): Response
{
    return $this->render('chabeb/ahmed/emploi/show_etudiant.html.twig', [
        'emplois' => $emploiRepository->findAll(),
    ]);
}
// Afficher la liste des event pour les parent
#[Route('/parent/emplois', name: 'emploi_show_parent', methods: ['GET'])]
public function showParent(EmploiRepository $emploiRepository): Response
{
    return $this->render('chabeb/ahmed/emploi/show_parent.html.twig', [
        'emplois' => $emploiRepository->findAll(),
    ]);
}
// Afficher la liste des event pour les parent
#[Route('/enseignant/emplois', name: 'emploi_show_teacher', methods: ['GET'])]
public function showTeacher(EmploiRepository $emploiRepository): Response
{
    return $this->render('chabeb/ahmed/emploi/show_prof.html.twig', [
        'emplois' => $emploiRepository->findAll(),
    ]);
}
}