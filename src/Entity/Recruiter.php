<?php

namespace App\Entity;

use App\Adapter\Doctrine\Repository\RecruiterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RecruiterRepository::class)]
class Recruiter extends User
{
    public function getRoles(): array
    {
        return ["ROLE_USER","ROLE_RECRUITER"];
    }

    #[ORM\OneToMany(mappedBy: 'recruiter', targetEntity: Offer::class)]
    private Collection $offers;

    public function getOffers(): Collection
    {
        return $this->offers;
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $companyName = null;

    public function __construct()
    {
        parent::__construct(); // Initialize registeredAt from parent
        $this->offers = new ArrayCollection();
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(?string $companyName): static
    {
        $this->companyName = $companyName;
        return $this;
    }
}
