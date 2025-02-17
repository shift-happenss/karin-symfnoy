<?php

namespace App\Controller;

use App\Entity\Psy;
use App\Form\PsyType;
use App\Repository\PsyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/psy')]
class PsyController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PsyRepository $psyRepository
    ) {}

// Affiche la liste des Psy
   
    #[Route('/', name: 'psy_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('chabeb/ines/psy/index.html.twig', [
            'psies' => $this->psyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'psy_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $psy = new Psy();
        $form = $this->createForm(PsyType::class, $psy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($psy);
            $this->entityManager->flush();

            $this->addFlash('success', 'Psy créé avec succès.');
            return $this->redirectToRoute('psy_index');
        }

        return $this->render('chabeb/ines/psy/new.html.twig', [
            'form' => $form->createView(),
           //'psy' => $psy,
            //'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'psy_show', methods: ['GET'])]
    public function show(Psy $psy): Response
    {
        return $this->render('chabeb/ines/psy/show.html.twig', [
            'psy' => $psy,
            'consultations' => $psy->getConsultations(), // Récupérer les consultations du psy

        ]);
    }

    #[Route('/{id}/edit', name: 'psy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Psy $psy): Response
    {
        $form = $this->createForm(PsyType::class, $psy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Psy mis à jour avec succès.');
            return $this->redirectToRoute('psy_index');
        }


        return $this->render('chabeb/ines/psy/edit.html.twig', [
            'form' => $form->createView(),
            'psy' => $psy,
        ]);
    }

    #[Route('/{id}', name: 'psy_delete', methods: ['POST'])]
    public function delete(Request $request, Psy $psy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $psy->getId(), $request->request->get('_token'))) {
               // Supprimer toutes les consultations associées
        foreach ($psy->getConsultations() as $consultation) {
            $entityManager->remove($consultation);
        }
            $this->entityManager->remove($psy);
            $this->entityManager->flush();

            $this->addFlash('success', 'Psy supprimé avec succès.');
        }

        return $this->redirectToRoute('psy_index');
    }
}