<?php
namespace App\Controller;

use App\Entity\Examen;
use App\Entity\ReponseSoumise;
use App\Entity\Question;
use App\Entity\Reponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasserExamenController extends AbstractController
{
    #[Route('/examen/{id}', name: 'passer_examen', methods: ['GET', 'POST'])]
    public function passerExamen(Request $request, Examen $examen, EntityManagerInterface $entityManager): Response
    {
        $questions = $examen->getQuestions();
        $note = 0;
        $totalQuestions = count($questions);

        if ($request->isMethod('POST')) {
            foreach ($questions as $question) {
                // Récupération des réponses soumises
                $reponsesSoumises = $request->request->all()['question_' . $question->getId()] ?? [];
                $reponsesSoumises = $this->normalizeReponses($reponsesSoumises);

                // Récupérer toutes les réponses correctes pour cette question
                $bonnesReponses = $entityManager->getRepository(Reponse::class)->findBy(['question' => $question]);

                // Extraire les valeurs de 'est_correcte' sous forme de **tableau**
                $bonnesReponsesValeurs = [];
                foreach ($bonnesReponses as $reponse) {
                    $bonnesReponsesValeurs = array_merge($bonnesReponsesValeurs, explode(',', $reponse->getEstCorrecte()));
                }

                // Normalisation : supprimer les espaces et trier
                $bonnesReponsesValeurs = array_map('trim', $bonnesReponsesValeurs);
                sort($bonnesReponsesValeurs);
                sort($reponsesSoumises);

                // Création et stockage de la réponse soumise
                $reponseSoumise = new ReponseSoumise();
                $reponseSoumise->setQuestion($question);
                $reponseSoumise->setTexte(implode(",", $reponsesSoumises));
                $reponseSoumise->setEstCorrect(false); // Par défaut incorrect

                // Comparer les réponses soumises avec les bonnes réponses
                if ($reponsesSoumises === $bonnesReponsesValeurs) {
                    $reponseSoumise->setEstCorrect(true);
                    $note++; // Incrémenter la note si correct
                }

                $entityManager->persist($reponseSoumise);
            }

            // Calcul de la note finale sur 20
            $noteFinale = $totalQuestions > 0 ? round(($note / $totalQuestions) * 20, 2) : 0;

            $examen->setNote($noteFinale);
            $entityManager->persist($examen);
            $entityManager->flush();

            return $this->redirectToRoute('resultat_examen', ['id' => $examen->getId()]);
        }

        return $this->render('chabeb/houssem/passer_examen/passer.html.twig', [
            'examen' => $examen,
            'questions' => $questions,
        ]);
    }

    #[Route('/{id}/resultat', name: 'resultat_examen', methods: ['GET'])]
    public function resultatExamen(Examen $examen): Response
    {
        return $this->render('chabeb/houssem/passer_examen/resultat.html.twig', [
            'examen' => $examen,
            'note' => $examen->getNote(),
        ]);
    }

    // Normaliser les réponses (convertir string en array si nécessaire)
    private function normalizeReponses($reponses): array
    {
        if (is_null($reponses)) {
            return [];
        }
        return is_array($reponses) ? array_map('trim', $reponses) : [trim($reponses)];
    }
}
