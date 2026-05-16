<?php

namespace App\Tests\Unit;

use App\Adapter\InMemory\Repository\JobSeekerRepository;
use App\Entity\JobSeeker;
use App\UseCase\RegisterJobSeeker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class JobSeekerTest extends TestCase
{
    private RegisterJobSeeker $registerJobSeeker;

    protected function setUp(): void
    {
        $validator = Validation::createValidator();

        $this->registerJobSeeker = new RegisterJobSeeker(
            new JobSeekerRepository(),
            $validator ,
            $this->createMock('Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface')
        );
    }



    /**
     * @dataProvider jobSeekerProvider
     */
    /** @dataProvider jobSeekerDataProvider */
    public function testRegisterJobSeeker(array $data, ?string $expectedException = null, ?string $expectedMessage =null): void
    {
        $jobSeeker = new JobSeeker();
        $jobSeeker->setEmail($data['email'] ?? null);
        $jobSeeker->setFirstName($data['firstName'] ?? null);
        $jobSeeker->setLastName($data['lastName'] ?? null);
        $jobSeeker->setPlainPassword($data['password'] ?? null);

            if ($expectedException) {
                $this->expectException($expectedException);
            }

            if($expectedMessage) {
                $this->expectExceptionMessage($expectedMessage);
            }

           $saved = $this->registerJobSeeker->execute($jobSeeker);

            $this->assertSame($jobSeeker, $saved, 'JobSeeker was not registered correctly');

    }

    public static function jobSeekerDataProvider(): array
    {
        return [
            [
                ['email' => 'email@email.com', 'firstName' => 'John', 'lastName' => 'Doe', 'password' => 'Password123!']
            ,  null, null
            ],
            [
                ['email' => '', 'firstName' => 'John', 'lastName' => 'Doe', 'password' => 'Password123!'], \InvalidArgumentException::class
                , 'email: This value should not be blank.'
            ]
            ,
            [
                ['email' => 'email@email.com', 'firstName' => '', 'lastName' => 'Doe', 'password' => 'Password123!'], \InvalidArgumentException::class
                , 'firstName: This value should not be blank.'
            ],
            [
                ['email' => 'email@email.com', 'firstName' => '', 'lastName' => 'Doe', 'password' => 'Passwor!'], \InvalidArgumentException::class
                , 'firstName: This value should not be blank.' . "\n" . 'plainPassword: Password must contain at least 1 uppercase and 1 lowercase, one number and one special character, and at least 8 characters'
            ],
        ];
    }
//    public function testFindOneByEmail(): void
//    {
//        // 1. Arrange: Create and register a JobSeeker
//        $email = 'findme@example.com';
//        $jobSeeker = new JobSeeker();
//        $jobSeeker->setEmail($email);
//        $jobSeeker->setFirstName('John');
//        $jobSeeker->setLastName('Doe');
//        $jobSeeker->setPlainPassword('Password123!');
//
//        // Save it using your use case
//        $this->registerJobSeeker->execute($jobSeeker);
//
//        // 2. Act: Try to find that same seeker by email via the repository
//        // Note: You'll need access to the repository instance.
//        // If it's private in your UseCase, you might want to mock it or keep a reference.
//        $repository = new JobSeekerRepository();
//        $repository->save($jobSeeker); // Directly saving to repo for isolation
//
//        $foundJobSeeker = $repository->findOneByEmail($email);
//
//        // 3. Assert: Verify the data matches
//        $this->assertInstanceOf(JobSeeker::class, $foundJobSeeker);
//        $this->assertEquals($email, $foundJobSeeker->getEmail());
//        $this->assertEquals('John', $foundJobSeeker->getFirstName());
//    }
//
//    public function testFindOneByEmailReturnsNullIfNotFound(): void
//    {
//        $repository = new JobSeekerRepository();
//        $found = $repository->findOneByEmail('nonexistent@test.com');
//
//        $this->assertNull($found, 'Should return null for unknown email');
//    }


}