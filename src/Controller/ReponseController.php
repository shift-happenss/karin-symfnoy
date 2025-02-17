<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponses')]
class ReponseController extends AbstractController
{
   
   // Afficher la liste des réponses d'une question
#[Route('/question/{questionId}', name: 'reponse_index', methods: ['GET'])]
public function index(int $questionId, ReponseRepository $reponseRepository, QuestionRepository $questionRepository): Response
{
    $question = $questionRepository->find($questionId);

    if (!$question) {
        throw $this->createNotFoundException("La question avec l'ID $questionId n'existe pas.");
    }

    $reponses = $reponseRepository->findBy(['question' => $question]);

    return $this->render('chabeb/houssem/reponse/index.html.twig', [
        'reponses' => $reponses,
        'question' => $question,
        'questionId' => $questionId, // Ajout de cette ligne
    ]);
}

    // Ajouter une nouvelle réponse
    #[Route('/question/{questionId}/new', name: 'reponse_new', methods: ['GET', 'POST'])]
    public function new(int $questionId, Request $request, EntityManagerInterface $entityManager, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($questionId);

        if (!$question) {
            throw $this->createNotFoundException("La question avec l'ID $questionId n'existe pas.");
        }

        $reponse = new Reponse();
        $reponse->setQuestion($question);
        $reponse->setTexte(' '); // Permet de stocker NULL par défaut

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('reponse_index', ['questionId' => $questionId]);
        }

        return $this->render('chabeb/houssem/reponse/new.html.twig', [
            'form' => $form->createView(),
            'question' => $question,
        ]);
    }

    // Afficher les détails d'une réponse
    #[Route('/{id}', name: 'reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('chabeb/houssem/reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    // Modifier une réponse
    #[Route('/{id}/edit', name: 'reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reponse_index', ['questionId' => $reponse->getQuestion()->getId()]);
        }

        return $this->render('chabeb/houssem/reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer une réponse
    #[Route('/{id}/delete', name: 'reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $reponse->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('reponse_index', ['questionId' => $reponse->getQuestion()->getId()]);
        }

        $entityManager->remove($reponse);
        $entityManager->flush();

        return $this->redirectToRoute('reponse_index', ['questionId' => $reponse->getQuestion()->getId()]);
    }
}
