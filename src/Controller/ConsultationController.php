<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Form\ConsultationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConsultationRepository;
use App\Repository\PsyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/consultation')]
class ConsultationController extends AbstractController
{
   public function __construct(
        private EntityManagerInterface $entityManager,
        private ConsultationRepository $consultationRepository,
        private PsyRepository $psyRepository
    ) {}

    // Affiche la liste des consultations
    #[Route('/', name: 'consultation_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('chabeb/ines/consultation/index.html.twig', [
            'consultations' => $this->consultationRepository->findAll(),
            'psies' => $this->psyRepository->findAll(), // Liste des psys
        ]);
    }

    // Crée une nouvelle consultation
    #[Route('/new', name: 'consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $consultation = new Consultation();
        
        // Création du formulaire pour l'entité Consultation
        $form = $this->createForm(ConsultationType::class, $consultation);

        // Traitement de la requête
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister l'entité Consultation en base de données
            $this->entityManager->persist($consultation);
            $this->entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Consultation créée avec succès.');

            // Redirection vers la liste des consultations
            return $this->redirectToRoute('consultation_index');
        }

        // Rendre la vue avec le formulaire
        return $this->render('chabeb/ines/consultation/new.html.twig', [
            'form' => $form->createView(),
            'psies' => $this->psyRepository->findAll(), // Liste des psys
        ]);
    }

    // Affiche une consultation
    #[Route('/{id}', name: 'consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('chabeb/ines/consultation/show.html.twig', [
            'consultation' => $consultation,
            'psies' => $this->psyRepository->findAll(), // Liste des psys
        ]);
    }

    // Édite une consultation
    #[Route('/{id}/edit', name: 'consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation): Response
    {
        // Création du formulaire pour l'entité Consultation
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour de l'entité en base de données
            $this->entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Consultation mise à jour avec succès.');

            // Redirection vers la liste des consultations
            return $this->redirectToRoute('consultation_index');
        }

        // Rendre la vue avec le formulaire
        return $this->render('chabeb/ines/consultation/edit.html.twig', [
            'form' => $form->createView(),
            'consultation' => $consultation,
            'psies' => $this->psyRepository->findAll(), // Liste des psys
        ]);
    }

    // Supprime une consultation
    #[Route('/{id}', name: 'consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $consultation->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($consultation);
            $this->entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Consultation supprimée avec succès.');
        }

        // Redirection vers la liste des consultations
        return $this->redirectToRoute('consultation_index');
    }

    // Modifie le statut d'une consultation
    #[Route('/consultation/{id}/edit-status', name: 'consultation_edit_status', methods: ['GET', 'POST'])]
    public function editStatus(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $newStatus = $request->request->get('status'); // Récupérer le nouveau statut
            if ($newStatus) {
                $consultation->setStatus($newStatus);
                $entityManager->flush();
            }

            return $this->redirectToRoute('psy_show', ['id' => $consultation->getPsy()->getId()]);
        }

        return $this->render('chabeb/ines/consultation/edit_status.html.twig', [
            'consultation' => $consultation,
        ]);
    }
}
