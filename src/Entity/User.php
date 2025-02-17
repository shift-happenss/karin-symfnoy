<?php



  
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: "bigint")]
    private ?int $numtel = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

   

    public function getRoles(): array
{
    $roleMapping = [
        'teacher' => 'ROLE_TEACHER',
        'student' => 'ROLE_STUDENT',
        'parent'  => 'ROLE_PARENT',
        'admin'   => 'ROLE_ADMIN',
    ];

    $roles = [$roleMapping[$this->role] ?? 'ROLE_USER']; // Mapping du rôle stocké

    if (!in_array('ROLE_USER', $roles)) {
        $roles[] = 'ROLE_USER';
    }
    
    return $roles;
}


    public function eraseCredentials(): void
    {
        // Symfony demande cette méthode, mais elle peut être vide
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
    
        // Debugging: Log the submitted ID
        dump('Submitted ID:', $credentials['id']);
    
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $credentials['id']]);
    
        // Debugging: Log the retrieved user
        dump('Retrieved User:', $user);
    
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('ID could not be found.');
        }
    
        return $user;
    }
public function getUserIdentifier(): string
{
    return (string) $this->id;  // 🔹 Retourne l'ID au lieu de l'email
}

}
