<?php

//declare(strict_types=1);

namespace App\Tests\Integration;

use App\Entity\JobSeeker;
use App\UseCase\RegisterJobSeeker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
class RegisterJobSeekerTest extends WebTestCase
{

    public function testSomething(): void
    {// 1. Boot the Symfony Kernel to access the container
        self::bootKernel();
        $container = static::getContainer();
        $faker = \Faker\Factory::create();

        // 2. Get the Service (Use Case) directly from the container
        $registerJobSeekerService = $container->get(RegisterJobSeeker::class);

        //fake data
        $firstName =  $faker->firstName();
        $lastName =  $faker->lastName();
        $email =  $faker->unique()->safeEmail();
        $plainPassword = 'Password123!';
       

        // 3. Create a JobSeeker entity and set its properties
        $JobSeeker = new JobSeeker();
        $JobSeeker->setFirstName($firstName);
        $JobSeeker->setLastName($lastName);
        $JobSeeker->setEmail($email);
        $JobSeeker->setPlainPassword($plainPassword);

        // 4. ACT: Execute the business logic
        // This runs the validation, hashes the password, and calls the Gateway/Database
        $registerJobSeekerService->execute($JobSeeker);

        // 5. ASSERT: Check the side effects in the database
        $user = $container->get(\App\Adapter\Doctrine\Repository\JobSeekerRepository::class)->findOneByEmail($email);
        $this->assertNotNull($user, 'The JobSeeker was not found in the database after registration.');


        // Assert that the user was created and has the expected data
        $this->assertNotNull($user, 'The JobSeeker was not persisted to the database.');
        $this->assertSame($firstName, $user->getFirstName());

        // Verify the service handled hashing correctly
        $passwordHasher = $container->get('security.user_password_hasher');
        $this->assertTrue(
            $passwordHasher->isPasswordValid($user, $plainPassword),
            'The service failed to hash the password before saving.'
        );


        $this->assertNull($user->getPlainPassword(), 'The plain password was not cleared.');

    }

    /**
     * @return void
     * @dataProvider provideBadRequest
     */
    public function testBadRequest(array $formData): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $faker = \Faker\Factory::create();

        // 2. Get the Service (Use Case) directly from the container
        $registerJobSeekerService = $container->get(RegisterJobSeeker::class);
        $JobSeeker = new JobSeeker();
        $JobSeeker->setFirstName($formData['registration[firstName]'] ?? null);
        $JobSeeker->setLastName($formData['registration[lastName]'] ?? null);
        $JobSeeker->setEmail($formData['registration[email]'] ?? null);
        $JobSeeker->setPlainPassword($formData['registration[plainPassword]'] ?? null);

        $this->expectException(\InvalidArgumentException::class);
        $registerJobSeekerService->execute($JobSeeker);

    }


   public static function provideBadRequest(): array
    {
        return [
            [
                ['registration[firstName]' => '', 'registration[lastName]' => '', 'registration[email]' => 'invalid-email', 'registration[plainPassword]' => 'weak']],
            [
                ['registration[firstName]' => 'A', 'registration[lastName]' => 'B', 'registration[email]' => 'email', 'registration[plainPassword]' => 'weak']],
            [
                ['registration[firstName]' => 'John', 'registration[lastName]' => 'Doe', 'registration[email]' => 'email', 'registration[plainPassword]' => 'weak']],
            [
                ['registration[firstName]' => 'John', 'registration[lastName]' => 'Doe', 'registration[email]' => '', 'registration[plainPassword]' => 'weak']],
        ]
            ;
    }

//    public function testSuccessfulRegistration(): void
//    {
//        self::bootKernel();
//        $container = static::getContainer();
//        $faker = \Faker\Factory::create();
//
//
//
//        // 2. Get the Service (Use Case) directly from the container
//        $registerJobSeekerService = $container->get(RegisterJobSeeker::class);
//        //fake data
//
//    }
}
