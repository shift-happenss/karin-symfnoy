<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud_ressource')]
class RessourceController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private RessourceRepository $ressourceRepository;

    public function __construct(EntityManagerInterface $entityManager, RessourceRepository $ressourceRepository)
    {
        $this->entityManager = $entityManager;
        $this->ressourceRepository = $ressourceRepository;
    }

    #[Route('/', name: 'ressource_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $ressources = $this->ressourceRepository->findAll();
        $titre = $request->query->get('titre', '');
        return $this->render('chabeb/aziz/ressource/index.html.twig', [
            'ressources' => $ressources,
            'titre' => $titre,
        ]);
    }

    #[Route('/new', name: 'ressource_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $ressource = new Ressource();
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($ressource);
            $this->entityManager->flush();

            $this->addFlash('success', 'Ressource créé avec succès.');

            return $this->redirectToRoute('ressource_index');
        }

        return $this->render('chabeb/aziz/ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'ressource_show', methods: ['GET'])]
    public function show(Ressource $ressource): Response
    {
        return $this->render('chabeb/aziz/ressource/show.html.twig', [
            'ressource' => $ressource,
        ]);
    }

    #[Route('/{id}/edit', name: 'ressource_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Ressource $ressource): Response
    {
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Ressource mis à jour avec succès.');

            return $this->redirectToRoute('ressource_index');
        }

        return $this->render('chabeb/aziz/ressource/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'ressource_delete', methods: ['POST'])]
    public function delete(Request $request, Ressource $ressource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($ressource);
            $this->entityManager->flush();
            $this->addFlash('success', 'Ressource supprimé avec succès.');
        }

        return $this->redirectToRoute('ressource_index');
    }
    #[Route('/user/ressources', name: 'ress_show_user', methods: ['GET'])]
public function showuser(RessourceRepository $ressourceRepository): Response
{
    return $this->render('chabeb/aziz/ressource/show_user.html.twig', [
        'ressources' => $ressourceRepository->findAll(),
    ]);
}
}
