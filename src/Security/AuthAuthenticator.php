<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // Récupérer les credentials depuis la requête
        $id = $request->request->get('_id'); // Champ '_id' dans le formulaire

        if (!$id) {
            throw new \InvalidArgumentException('User ID is required.');
        }

        $password = $request->request->get('_password');
        $csrfToken = $request->request->get('_csrf_token');

        // Vérification des identifiants "admin"
        if ($id === 'admin' && $password === 'admin') {
            // Créer un utilisateur virtuel avec rôle ROLE_ADMIN
            $user = new class implements UserInterface {
                public function getRoles(): array
                {
                    return ['ROLE_ADMIN']; // Rôle administrateur
                }

                public function getPassword(): string
                {
                    return 'admin'; // Mot de passe virtuel "admin"
                }

                public function getUsername(): string
                {
                    return 'admin'; // Identifiant virtuel
                }

                public function eraseCredentials()
                {
                    // Aucune donnée à effacer pour l'utilisateur virtuel
                }

                public function getUserIdentifier(): string
                {
                    return 'admin'; // Identifiant unique
                }
            };

            return new Passport(
                new UserBadge('admin'), // Utilisateur virtuel
                new PasswordCredentials($password), // Le mot de passe "admin"
                [
                    new CsrfTokenBadge('authenticate', $csrfToken),
                    new RememberMeBadge(),
                ]
            );
        }

        // Si ce n'est pas l'admin, rechercher l'utilisateur dans la base de données
        return new Passport(
            new UserBadge($id), // Chercher l'utilisateur avec l'ID
            new PasswordCredentials($password), // Vérifier le mot de passe dans la base de données
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();
        $roles = $user->getRoles();

        // Rediriger selon le rôle de l'utilisateur
        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('admin'));
        } elseif (in_array('ROLE_STUDENT', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('profileS'));
        } elseif (in_array('ROLE_TEACHER', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('profileT'));
        } elseif (in_array('ROLE_PARENT', $roles)) {
            return new RedirectResponse($this->urlGenerator->generate('profileP'));
        }

        // Si aucun rôle spécifique n'est trouvé, rediriger vers une page par défaut
        return new RedirectResponse($this->urlGenerator->generate('default_route'));
    }

    protected function getLoginUrl(Request $request): string
    {
        // URL de la page de connexion
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function getCredentials(Request $request)
    {
        // Retourner les identifiants récupérés du formulaire de connexion
        return [
            'id' => $request->request->get('_id'),
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $userId = $credentials['id'];

        // Chercher l'utilisateur dans la base de données via le UserProvider
        try {
            $user = $userProvider->loadUserByIdentifier($userId);
        } catch (\Symfony\Component\Security\Core\Exception\UsernameNotFoundException $e) {
            throw new \Symfony\Component\Security\Core\Exception\AuthenticationException('Invalid credentials.');
        }

        return $user;
    }
}
