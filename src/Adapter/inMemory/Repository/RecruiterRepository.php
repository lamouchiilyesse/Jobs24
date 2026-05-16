<?php

namespace App\Adapter\inMemory\Repository;

use App\Entity\Recruiter;
use App\Gateway\RecruiterGateway;

class RecruiterRepository implements RecruiterGateway
{
    private static array $storage = [];
    private static int $nextId = 1;

    public function register(Recruiter $recruiter): void
    {
        if ($recruiter->getId() === null) {
            $ref = new \ReflectionObject($recruiter);
            if ($ref->hasProperty('id')) {
                $prop = $ref->getProperty('id');
                $prop->setAccessible(true);
                $prop->setValue($recruiter, self::$nextId++);
            }
        }

        self::$storage[$recruiter->getId()] = $recruiter;
    }

    public function find(int $id): ?Recruiter
    {
        return self::$storage[$id] ?? null;
    }
}