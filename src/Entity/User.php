<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "disc", type: "string")]
#[ORM\DiscriminatorMap([
    "job_seeker" => JobSeeker::class,
    "recruiter" => Recruiter::class
])]
abstract class User implements UserInterface,PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $firstName = null;

    /**
     * @var string|null
     * #[ORM\Column]
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $lastName = null;
    /**
     * @var string|null
     * #[ORM\Column(unique : true)]
     */
    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: true)]
    protected ?string $email = null;

    /**
     * @var string|null
     * #[ORM\Column]
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $password = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $plainPassword = null;

    /**
     * @var \DateTimeInterface
     * #[ORM\Column(type:'datetime_immutable']
     */
    #[ORM\Column(type: 'datetime_immutable')]
    protected \DateTimeInterface $registeredAt;

    /**
     * This is required by UserInterface.
     * It returns the unique ID for the user (the email).
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }
    abstract public function getRoles(): array;


    /**
     * @return void
     */
    public function __construct()
    {
        $this->registeredAt = new \DateTimeImmutable();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getRegisteredAt(): \dateTimeInterface
    {
        return $this->registeredAt;
    }


}
