<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Examen;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/questions')]
class QuestionController extends AbstractController
{
    // 🔹 Afficher toutes les questions
    #[Route('/', name: 'question_all', methods: ['GET'])]
    public function allQuestions(QuestionRepository $questionRepository): Response
    {
        return $this->render('chabeb/houssem/question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
            'examenId' => null,
        ]);
    }

    // 🔹 Afficher les questions d'un examen donné
    #[Route('/examen/{examenId}', name: 'question_index', methods: ['GET'])]
    public function index(int $examenId, QuestionRepository $questionRepository, EntityManagerInterface $entityManager): Response
    {
        $examen = $entityManager->getRepository(Examen::class)->find($examenId);
        if (!$examen) {
            throw $this->createNotFoundException('Examen non trouvé.');
        }

        return $this->render('chabeb/houssem/question/index.html.twig', [
            'questions' => $questionRepository->findBy(['examen' => $examenId]),
            'examenId' => $examenId,
            'examenTitre' => $examen->getTitre(),
        ]);
    }

    // 🔹 Créer une nouvelle question avec liste déroulante des examens
    #[Route('/new', name: 'question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_all');
        }

        return $this->render('chabeb/houssem/question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    // 🔹 Afficher une question
    #[Route('/{id}', name: 'question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('chabeb/houssem/question/show.html.twig', [
            'question' => $question,
        ]);
    }

    // 🔹 Modifier une question
    #[Route('/{id}/edit', name: 'question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('question_index', ['examenId' => $question->getExamen()->getId()]);
        }

        return $this->render('chabeb/houssem/question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'examenId' => $question->getExamen()->getId(),
        ]);
    }

    // 🔹 Supprimer une question
    #[Route('/{id}/delete', name: 'question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $examenId = $question->getExamen()->getId();

            $entityManager->remove($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index', ['examenId' => $examenId]);
        }

        return $this->redirectToRoute('question_index');
    }
}
