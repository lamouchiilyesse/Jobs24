<?php

namespace App\UseCase;

use App\Entity\Offer;
use App\Gateway\OfferGateway;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PublishOffer
{
    public function __construct(
        private readonly OfferGateway $offerGateway,
        private readonly ValidatorInterface $validator,
    ) {}

    public function execute(Offer $offer): Offer
    {
        // One call to validate everything!
        $violations = $this->validator->validate($offer);

        if (count($violations) > 0) {
            $messages = [];
            foreach ($violations as $violation) {
                $messages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }
            throw new \InvalidArgumentException(implode(', ', $messages));
        }

        $this->offerGateway->publish($offer);
        return $offer;
    }
}
//
//namespace App\UseCase;
//
//use App\Entity\Offer;
//use Symfony\Component\Validator\Validator\ValidatorInterface;
//use function PHPUnit\Framework\throwException;
//
//class PublishOffer
//{
//        public function __construct(
//            private readonly \App\Gateway\OfferGateway $offerGateway,
//            ValidatorInterface $validator,
//        )
//        {
//            $this->validator = $validator;
//        }
//
//        public function execute(\App\Entity\Offer $offer): Offer
//        {
//            $messages = [];
//
//            $violations = $this->validator->validate($offer->getTitle(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] ="title" . $v->getMessage();
//            }
//
//            $violations = $this->validator->validate($offer->getCompanyDescription(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] = 'companyDescription: ' . $v->getMessage();
//            }
//
//             $violations = $this->validator->validate($offer->getJobDescription(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] = 'jobDescription: ' . $v->getMessage();
//            }
//
//             $violations = $this->validator->validate($offer->getmissions(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] = 'missions: ' . $v->getMessage();
//            }
//
//             $violations = $this->validator->validate($offer->getTasks(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] = 'tasks: ' . $v->getMessage();
//            }
//            $violations = $this->validator->validate($offer->getProfile(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] = 'profile: ' . $v->getMessage();
//            }
//            $violations = $this->validator->validate($offer->getMaxSalary(), [
//                new \Symfony\Component\Validator\Constraints\NotBlank()
//                ,new \Symfony\Component\Validator\Constraints\Positive()
//            ]);
//            foreach ($violations as $v) {
//                $messages[] = 'MaxSalary: ' . $v->getMessage();
//            }
//            $violations = $this->validator->validate($offer->getMinSalary(), [
//                new \Symfony\Component\Validator\Constraints\NotBlank(),
//                new \Symfony\Component\Validator\Constraints\Positive(),
//                new \Symfony\Component\Validator\Constraints\LessThan(propertyPath: 'maxSalary')
//            ]);
//
//            foreach ($violations as $v) {
//                $messages[] = 'MinSalary: ' . $v->getMessage();
//            }
//            $violations = $this->validator->validate($offer->getSoftSkills(), [new \Symfony\Component\Validator\Constraints\NotBlank()]);
//            foreach ($violations as $v) {
//                $messages[] = 'SoftSkills: ' . $v->getMessage();
//            }
//
//            foreach ($violations as $v) {
//                $messages[] = 'Salary: ' . $v->getMessage();
//            }
//            $violations = $this->validator->validate($offer->isRemote(), [new \Symfony\Component\Validator\Constraints\NotNull()]);
//            foreach ($violations as $v) {
//                $messages[] = 'remote: ' . $v->getMessage();
//            }
//
//            if (($max = $offer->getMaxSalary()) !== null && ($min = $offer->getMinSalary()) !== null) {
//                if ($max < $min) {
//                    $messages[] = 'Max salary must be greater than or equal to min salary';
//
//                }
//            }
//
//            if (count($messages) > 0) {
//                throw new \InvalidArgumentException(implode(', ', $messages));
//            }
//
//            $this->offerGateway->publish($offer);
//            return $offer;
//
//        }

