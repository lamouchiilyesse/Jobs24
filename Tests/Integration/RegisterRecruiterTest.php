<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\inMemory\Repository\RecruiterRepository;
use App\Entity\Recruiter;
use App\UseCase\RegisterRecruiter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface;

class RegisterRecruiterTest extends KernelTestCase
{
    public function testRegistration(): void
    {
        // 1. Boot the Symfony Kernel to access the container
        self::bootKernel();
        $container = static::getContainer();
        $faker = \Faker\Factory::create();

        // 2. Get the Service (Use Case) directly from the container
        $registerRecruiterService = $container->get(RegisterRecruiter::class);

        //fake data
        $firstName =  $faker->firstName();
        $lastName =  $faker->lastName();
        $email =  $faker->unique()->safeEmail();
        $plainPassword = 'Password123!';
        $companyName =  $faker->company();

        // 3. Create a Recruiter entity and set its properties
        $recruiter = new Recruiter();
        $recruiter->setFirstName($firstName);
        $recruiter->setLastName($lastName);
        $recruiter->setEmail($email);
        $recruiter->setCompanyName($companyName);
        $recruiter->setPlainPassword($plainPassword);

        // 4. ACT: Execute the business logic
        // This runs the validation, hashes the password, and calls the Gateway/Database
        $registerRecruiterService->execute($recruiter);

        // 5. ASSERT: Check the side effects in the database
       $user = $container->get(\App\Adapter\Doctrine\Repository\RecruiterRepository::class)->findOneByEmail($email);
       $this->assertNotNull($user, 'The recruiter was not found in the database after registration.');


        // Assert that the user was created and has the expected data
        $this->assertNotNull($user, 'The recruiter was not persisted to the database.');
        $this->assertSame($firstName, $user->getFirstName());
        $this->assertSame($companyName, $user->getCompanyName());

        // Verify the service handled hashing correctly
        $passwordHasher = $container->get('security.user_password_hasher');
        $this->assertTrue(
            $passwordHasher->isPasswordValid($user, $plainPassword),
            'The service failed to hash the password before saving.'
        );


        $this->assertNull($user->getPlainPassword(), 'The plain password was not cleared.');

    }
}
