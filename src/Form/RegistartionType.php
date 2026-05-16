<?php

//declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

abstract class RegistartionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => true,
                'attr' => ['maxlength' => 50, 'minlength' => 2],
                'constraints' => [
                    new NotBlank(['message' => 'First name should not be blank']),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
                'attr' => ['maxlength' => 50, 'minlength' => 2],
                'constraints' => [
                    new NotBlank(['message' => 'Last name should not be blank']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'required' => true,
                'attr' => ['maxlength' => 100],
                'constraints' => [
                    new NotBlank(['message' => 'Email should not be blank']),
                    new Email(['message' => 'Please enter a valid email address']),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'attr' => ['maxlength' => 255, 'minlength' => 6],
                'constraints' => [
                    new NotBlank(['message' => 'Password should not be blank']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Password should be at least {{ limit }} characters long',
                        'max' => 255,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%\^&\-+=()!?])[A-Za-z\d@#$%\^&\-+=()!?]{8,}$/',
                        'message' => 'Password must contain at least 1 uppercase and 1 lowercase, one number and one special character, and at least 8 characters',
                    ]),
                ],
            ]);
    }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                "data_class" => null
            ]);
        }
}
