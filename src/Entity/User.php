<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * 
 * @ApiResource(attributes={
 *   "normalization_context"={"groups"={"read"}},
 *   "denormalization_context"={"groups"={"write"}},
 * })
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
 * @UniqueEntity("username", message="Impossible, cet utilisateur existe déjà !")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read", "write"})
     * @Assert\NotNull( message = "Veuillez renseigner votre nom")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read", "write"})
     * @Assert\NotNull(message = "Veuillez renseigner votre prenom")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"read", "write"})
     * @Assert\NotNull(message = "Veuillez renseigner email")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     * @Assert\Length(
     *     min = 8,
     *     minMessage= "Veuillez entrer un mot de passe avec au minimum {{ min }} caractères"
     * )
     * @Assert\NotNull(message= "Veuillez entrer un mot de passe")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read", "write"})
     */
    private $roles = ['ROLE_USER'];

    public function __construct() { }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): string {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
