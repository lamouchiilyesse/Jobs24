<?php

namespace App\UseCase;

use App\Entity\JobSeeker;
use App\Gateway\JobSeekerGateway;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterJobSeeker
{
    /**
     * @var JobSeekerGateway
     */
    private JobSeekerGateway $jobSeekerGateway;

    private ValidatorInterface $validator;

    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(
        JobSeekerGateway $jobSeekerGateway,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher) {
        $this->jobSeekerGateway = $jobSeekerGateway;
        $this->validator = $validator;
        $this->passwordHasher = $passwordHasher;

    }

    public function execute(JobSeeker $jobSeeker) : JobSeeker
    {
        $messages = [];

        // first name
        $violations = $this->validator->validate($jobSeeker->getFirstName(), [new NotBlank()]);
        foreach ($violations as $v) {
            $messages[] = 'firstName: ' . $v->getMessage();
        }

        // last name
        $violations = $this->validator->validate($jobSeeker->getLastName(), [new NotBlank()]);
        foreach ($violations as $v) {
            $messages[] = 'lastName: ' . $v->getMessage();
        }

        // plain password: not blank + regex
        $passwordConstraints = [
            new NotBlank(),
            new Regex([
                'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%\^&\-+=()!?])[A-Za-z\d@#$%\^&\-+=()!?]{8,}$/',
                'message' => 'Password must contain at least 1 uppercase and 1 lowercase, one number and one special character, and at least 8 characters',
            ])
        ];
        $violations = $this->validator->validate($jobSeeker->getPlainPassword(), $passwordConstraints);
        foreach ($violations as $v) {
            $messages[] = 'plainPassword: ' . $v->getMessage();
        }

        // email
        $violations = $this->validator->validate($jobSeeker->getEmail(), [new NotBlank(), new EmailConstraint()]);
        foreach ($violations as $v) {
            $messages[] = 'email: ' . $v->getMessage();
        }

        if (!empty($messages)) {
            throw new \InvalidArgumentException(implode("\n", $messages));
        }

        // hashPassword() takes the object AND the plain string
        $hashedPassword = $this->passwordHasher->hashPassword(
            $jobSeeker,
            $jobSeeker->getPlainPassword()
        );

        $jobSeeker->setPassword($hashedPassword);
        $jobSeeker->setPlainPassword(null);
        $this->jobSeekerGateway->register($jobSeeker);
        return $jobSeeker;
    }
}