<?php

namespace App\UseCase;

use App\Entity\Recruiter;
use App\Gateway\RecruiterGateway;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterRecruiter
{
    /**
     * @var RecruiterGateway
     */
    private RecruiterGateway $recruiterGateway;

    private ValidatorInterface $validator;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        RecruiterGateway $recruiterGateway
        , ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->recruiterGateway = $recruiterGateway;
        $this->validator = $validator;
        $this->passwordHasher = $passwordHasher;
    }
    public function execute(Recruiter $recruiter) : Recruiter
    {
        $messages = [];

        $violations = $this->validator->validate($recruiter->getFirstName(),
            [
            new NotBlank(),
            new Length(['min'=> 2 ,'max' => 50]),
                new Regex([
                    'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ\'\- ]+$/u',
                    'message' => 'First name can only contain letters, spaces, hyphens, and apostrophes',])
            ]);

        foreach ($violations as $v) {
            $messages[] = 'firstName: ' . $v->getMessage();
        }

        $violations = $this->validator->validate($recruiter->getLastName(), [new NotBlank()]);
        foreach ($violations as $v) {
            $messages[] = 'lastName: ' . $v->getMessage();
        }

        $violations= $this->validator->validate($recruiter->getCompanyName(), [new NotBlank()]);
        foreach ($violations as $v) {
            $messages[] = 'companyName: ' . $v->getMessage();
        }


        $passwordConstraints = [
            new NotBlank(),
            new Regex([
                'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%\^&\-+=()!?])[A-Za-z\d@#$%\^&\-+=()!?]{8,}$/',
                'message' => 'Password must contain at least 1 uppercase and 1 lowercase, one number and one special character, and at least 8 characters',
            ])
        ];
        $violations = $this->validator->validate($recruiter->getPlainPassword(), $passwordConstraints);
        foreach ($violations as $v) {
            $messages[] = 'plainPassword: ' . $v->getMessage();
        }

        $violations = $this->validator->validate($recruiter->getEmail(), [new NotBlank(), new EmailConstraint()]);
        foreach ($violations as $v) {
            $messages[] = 'email: ' . $v->getMessage();
        }

        if (!empty($messages)) {
            throw new \InvalidArgumentException(implode("\n", $messages));
        }

        $hashedPassword = $this->passwordHasher->hashPassword($recruiter, $recruiter->getPlainPassword());// Hash the password and set it on the recruiter entity
        $recruiter->setPassword($hashedPassword); // Set the hashed password on the recruiter entity
        $recruiter->eraseCredentials(); // Clear the plain password from memory
        $recruiter->setPlainPassword(null); // Clear the plain password from the entity
        $this->recruiterGateway->register($recruiter);
        return $recruiter;
    }
}