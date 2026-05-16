<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // src/Entity/Offer.php

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recruiter $recruiter = null;

    public function getRecruiter(): ?Recruiter
    {
        return $this->recruiter;
    }

    public function setRecruiter(?Recruiter $recruiter): self
    {
        $this->recruiter = $recruiter;
        return $this;
    }

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: "Company description cannot be empty.")]
    private ?string $companyDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Job title is required.")]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: "Job description is required.")]
    private ?string $jobDescription = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: "Missions are required.")]
    private ?string $missions = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: "Tasks are required.")]
    private ?string $tasks = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: "Profile requirements are required.")]
    private ?string $profile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Please list the necessary soft skills.")]
    private ?string $softSkills = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Minimum salary is required.")]
    #[Assert\Positive(message: "Salary must be a positive number.")]
    private ?int $minSalary = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: "Maximum salary is required.")]
    #[Assert\GreaterThan(
        propertyPath: "minSalary",
        message: "Max salary must be greater than min salary ({{ compared_value }})."
    )]
    private ?int $maxSalary = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotNull(message: "Please specify if the position is remote.")]
    private ?bool $remote = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->publishedAt = new \DateTimeImmutable();
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): void { $this->title = $title; }

    public function getCompanyDescription(): ?string { return $this->companyDescription; }
    public function setCompanyDescription(?string $companyDescription): self {
        $this->companyDescription = $companyDescription;
        return $this;
    }

    public function getJobDescription(): ?string { return $this->jobDescription; }
    public function setJobDescription(?string $jobDescription): self {
        $this->jobDescription = $jobDescription;
        return $this;
    }

    public function getmissions(): ?string { return $this->missions; }
    public function setmissions(?string $missions): self {
        $this->missions = $missions;
        return $this;
    }

    public function getTasks(): ?string { return $this->tasks; }
    public function setTasks(?string $tasks): self {
        $this->tasks = $tasks;
        return $this;
    }

    public function getProfile(): ?string { return $this->profile; }
    public function setProfile(?string $profile): self {
        $this->profile = $profile;
        return $this;
    }

    public function getSoftSkills(): ?string { return $this->softSkills; }
    public function setSoftSkills(?string $softSkills): self {
        $this->softSkills = $softSkills;
        return $this;
    }

    public function getMinSalary(): ?int { return $this->minSalary; }
    public function setMinSalary(?int $minSalary): self {
        $this->minSalary = $minSalary;
        return $this;
    }

    public function getMaxSalary(): ?int { return $this->maxSalary; }
    public function setMaxSalary(?int $maxSalary): self {
        $this->maxSalary = $maxSalary;
        return $this;
    }

    public function isRemote(): ?bool { return $this->remote; }
    public function setRemote(?bool $remote): self {
        $this->remote = $remote;
        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable { return $this->publishedAt; }
    public function setPublishedAt(\DateTimeImmutable $publishedAt): self {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable { return $this->deletedAt; }
    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self {
        $this->deletedAt = $deletedAt;
        return $this;
    }
}