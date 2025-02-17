<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user_create')]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User(); // ✅ Corrected entity name (uppercase "U")

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ✅ Save the user to the database
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'utilisateur ajouté avec succès.');
            return $this->redirectToRoute('home'); // ✅ Ensure "home" route exists
        }

        return $this->render('front/user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/user/{id}/edit', name: 'user_edit')]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Create the form based on the User entity
        $form = $this->createForm(UserType::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        // If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', ' modification avec succès.');
            // Redirect to a different page after editing (e.g., list of users)
            return $this->redirectToRoute('gestionusers');
        }

        // Render the template and pass the form to it
        return $this->render('front/register/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
    #[Route('/gestionusers', name: 'gestionusers')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs
        $users = $entityManager->getRepository(User::class)->findAll();

        // Passer les utilisateurs à la vue
        return $this->render('back/gestionusers/index.html.twig', [
            'users' => $users, // On passe la variable users à Twig
        ]);
    }
#[Route('/delete-user/{id}', name: 'delete_user')]
public function deleteUser(EntityManagerInterface $entityManager, int $id): Response
{
    $user = $entityManager->getRepository(User::class)->find($id);
    
    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $entityManager->remove($user);
    $entityManager->flush();

    return $this->redirectToRoute('gestionusers'); // Redirige vers la liste des utilisateurs
}

#[Route('/user/{id<\d+>}', name: 'user_show', methods: ['GET'])]
public function show(User $user): Response
{
    return $this->render('Profiles/show.html.twig', [
        'user' => $user,
    ]);
}



}    