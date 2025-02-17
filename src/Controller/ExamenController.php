<?php
// src/Controller/ExamenController.php
namespace App\Controller;

use App\Entity\Examen;
use App\Form\ExamenType;
use App\Repository\ExamenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter; // ✅ Ajout nécessaire

#[Route('/examens')]
class ExamenController extends AbstractController
{
    // Afficher la liste des examens
    #[Route('/', name: 'examen_index', methods: ['GET'])]
    public function index(ExamenRepository $examenRepository): Response
    {
        return $this->render('chabeb/houssem/examen/index.html.twig', [
            'examens' => $examenRepository->findAll(),
        ]);
    }

    // Créer un nouvel examen
    #[Route('/new', name: 'examen_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $examen = new Examen();
        $form = $this->createForm(ExamenType::class, $examen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($examen);
            $entityManager->flush();

            return $this->redirectToRoute('examen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chabeb/houssem/examen/new.html.twig', [
            'examen' => $examen,
            'form' => $form->createView(),
        ]);
    }

    // Afficher les détails d'un examen
    #[Route('/{id}', name: 'examen_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[ParamConverter('examen', class: Examen::class)] // ✅ Force la conversion de l'ID en Examen
    public function show(Examen $examen): Response
    {
        return $this->render('chabeb/houssem/examen/show.html.twig', [
            'examen' => $examen,
        ]);
    }

    // Modifier un examen
    #[Route('/{id}/edit', name: 'examen_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    #[ParamConverter('examen', class: Examen::class)] // ✅ Force la conversion de l'ID en Examen
    public function edit(Request $request, Examen $examen, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExamenType::class, $examen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('examen_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chabeb/houssem/examen/edit.html.twig', [
            'examen' => $examen,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un examen
    #[Route('/{id}/delete', name: 'examen_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    #[ParamConverter('examen', class: Examen::class)] // ✅ Force la conversion de l'ID en Examen
    public function delete(Request $request, Examen $examen, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examen->getId(), $request->request->get('_token'))) {
            $entityManager->remove($examen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('examen_index', [], Response::HTTP_SEE_OTHER);
    }
   
    #[Route('/cours/{coursId}/examens', name: 'examen_par_cours', methods: ['GET'])]
    public function examensParCours(int $coursId, ExamenRepository $examenRepository): Response
{
    // Récupérer les examens associés au cours
    $examens = $examenRepository->findBy(['cours' => $coursId]);

    // Si aucun examen n'est trouvé, vous pouvez lever une exception ou afficher un message
    if (empty($examens)) {
        throw $this->createNotFoundException('Aucun examen trouvé pour ce cours.');
    }

    // Afficher la vue Twig avec les examens
    return $this->render('chabeb/houssem/examen/examens_par_cours.html.twig', [
        'examens' => $examens,
        'coursId' => $coursId,
    ]);
}
#[Route('/{id}/passer', name: 'passer_examen', methods: ['GET'])]
    #[ParamConverter('examen', class: Examen::class)]
    public function passerExamen(Examen $examen): Response
{
    return $this->render('examen/passer.html.twig', [
        'examen' => $examen,
    ]);
}
}
