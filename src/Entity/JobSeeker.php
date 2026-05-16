<?php

namespace App\Entity;

use App\Adapter\Doctrine\Repository\JobSeekerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobSeekerRepository::class)]
class JobSeeker extends User
{
    public function getRoles(): array
    {
        return ["ROLE_USER","ROLE_JOB_SEEKER"];
    }
}
