<?php

namespace App\Adapter\InMemory\Repository;


use App\Entity\JobSeeker;
use App\Gateway\JobSeekerGateway;

class JobSeekerRepository implements JobSeekerGateway
{
    // Simple in-memory storage for tests
    private static array $storage = [];
    private static int $nextId = 1;

    public function register(JobSeeker $jobSeeker): void
    {
        // Simulate assigning an ID and persisting the entity
        if ($jobSeeker->getId() === null) {
            // reflectively set the protected id property or use a setter if available
            $ref = new \ReflectionObject($jobSeeker);
            if ($ref->hasProperty('id')) {
                $prop = $ref->getProperty('id');
                $prop->setAccessible(true);
                $prop->setValue($jobSeeker, self::$nextId++);
            }
        }

        self::$storage[$jobSeeker->getId()] = $jobSeeker;
    }

    // Helper for tests: find by id
    public function find(int $id): ?JobSeeker
    {
        return self::$storage[$id] ?? null;
    }
}
