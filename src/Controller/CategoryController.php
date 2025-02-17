<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud_categorie')]
class CategoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();
        $titre = $request->query->get('titre', '');
        return $this->render('chabeb/aziz/categorie/index.html.twig', [
            'categories' => $categories,
            'titre' => $titre,
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET','POST'])]
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Categorie créé avec succès.');

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('chabeb/aziz/categorie/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('chabeb/aziz/categorie/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Categorie mis à jour avec succès.');

            return $this->redirectToRoute('category_index');
        }

        return $this->render('chabeb/aziz/categorie/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
            $this->addFlash('success', 'Categorie supprimé avec succès.');
        }

        return $this->redirectToRoute('category_index');
    }
    //usershow
    #[Route('/user/category', name: 'category_show_user', methods: ['GET'])]
  public function showUser(CategoryRepository $categoryRepository): Response
{
    return $this->render('chabeb/aziz/categorie/show_user.html.twig', [
        'categorys' => $categoryRepository->findAll(),
    ]);
}
}
