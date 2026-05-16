<?php


namespace App\Tests\Unit;

use App\Adapter\InMemory\Repository\RecruiterRepository;
use App\Entity\Recruiter;
use App\UseCase\RegisterRecruiter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class RegisterRecruiterTest extends TestCase
{
    private RegisterRecruiter $registerRecruiter;

    protected function setUp(): void
    {
        // Use the in-memory repository implementation
        $validator = Validation::createValidator();
        $this->registerRecruiter = new RegisterRecruiter(
            new RecruiterRepository(),
            $validator,
            $this->createMock('Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface'),
        );
    }

    /**
     * @dataProvider recruiterDataProvider
     */
    public function testRegisterRecruiterWithData(array $data, ?string $expectedException = null, ?string $expectedMessage = null): void
    {
        $recruiter = new Recruiter();
        $recruiter->setEmail($data['email'] ?? null);
        $recruiter->setFirstName($data['firstName'] ?? null);
        $recruiter->setLastName($data['lastName'] ?? null);
        $recruiter->setCompanyName($data['companyName'] ?? null);
        $recruiter->setPlainPassword($data['password'] ?? null);

        if ($expectedException) {
            $this->expectException($expectedException);
        }
        if ($expectedMessage) {
            $this->expectExceptionMessageMatches('/' . preg_quote($expectedMessage, '/') . '/');
        }

           $saved = $this->registerRecruiter->execute($recruiter);

            $this->assertSame($recruiter, $saved, 'Recruiter was not registered correctly');





    }

    public static function recruiterDataProvider(): array
    {
        return [
            [
                ['email' => 'email@email.com', 'firstName' => 'John', 'lastName' => 'Doe','companyName' =>'siemens', 'password' => 'Password123!']
                , null, null
            ],
            [
                ['email' => '', 'firstName' => 'John', 'lastName' => 'Doe','companyName' =>'siemens', 'password' => 'Password123!'], \InvalidArgumentException::class
                , 'email: This value should not be blank.'
            ]
            ,
            [
                ['email' => 'email@email.com', 'firstName' => '', 'lastName' => 'Doe','companyName' =>'siemens',  'password' => 'Password123!'], \InvalidArgumentException::class
                , 'firstName: This value should not be blank.'
            ],
            [
                ['email' => 'email@email.com', 'firstName' => 'elyes', 'lastName' => 'Doe','companyName' =>'siemens',  'password' => 'Passwor!'], \InvalidArgumentException::class
                , 'plainPassword: Password must contain at least 1 uppercase and 1 lowercase, one number and one special character, and at least 8 characters'
            ],
            [
                ['email' => 'email@email.com', 'firstName' => 'elyes', 'lastName' => 'Doe','companyName' =>'',  'password' => 'Password1234!'] ,\InvalidArgumentException::class
                , 'companyName: This value should not be blank.'

            ]
        ];

    }
}

