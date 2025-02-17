<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session): Response
{
    // Get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // Last ID entered by the user
    $lastId = $authenticationUtils->getLastUsername();

    // Get the target path from session (if any)
    $targetPath = $session->get('_security.main.target_path');

    return $this->render('security/login.html.twig', [
        'last_id' => $lastId,
        'error' => $error,
        'target_path' => $targetPath, // Pass the target path to the view if needed
    ]);
}


    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method will be intercepted by the logout key in security.yaml.');
    }

    #[Route('/check-password/{id}', name: 'check_password')]
    public function checkPassword(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        int $id,
        Request $request
    ): Response {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new Response('User not found', 404);
        }

        $submittedPassword = $request->request->get('password');

        if ($passwordEncoder->isPasswordValid($user, $submittedPassword)) {
            return new Response('Password is correct!');
        } else {
            return new Response('Invalid password!', 401);
        }
    }
    
}
