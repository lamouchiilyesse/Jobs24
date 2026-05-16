<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyDescription',TextareaType::class, [
                'required' => true,
                'label' => 'Company Description',
               ' attrs'=> [
                    'rows' => 5,
                ],
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Job Title',
                'attrs' => [
                    'placeholder' => 'Enter the job title',
                ],
            ])
            ->add('jobDescription',TextareaType::class, [
                'required' => true,
                'label' => 'Job Description',
                ' attrs'=> [
                    'rows' => 5,
                ],
            ])
            ->add('missions',TextareaType::class, [
                'required' => true,
                'label' => 'Missions',
                ' attrs'=> [
                    'rows' => 5,
                ],
            ])
            ->add('tasks',TextareaType::class, [
                'required' => true,
                'label' => 'Tasks',
                ' attrs'=> [
                    'rows' => 5,
                ],
            ])
            ->add('profile',TextareaType::class, [
                'required' => true,
                'label' => 'Profile',
                ' attrs'=> [
                    'rows' => 5,
                ],
            ])
            ->add('softSkills',TextareaType::class, [
                'required' => true,
                'label' => 'Company Description',
                ' attrs'=> [
                    'rows' => 5,
                ],
            ])
            ->add('minSalary',NumberType::class, [
                'required' => true,
                'label' => 'Minimum Salary',
                new Type('integer'),
                'attr' => [
                    'placeholder' => 'Enter the minimum salary',
                ],
            ])
            ->add('maxSalary',NumberType::class, [
                'required' => true,
                'label' => 'Minimum Salary',
                new Type('integer'),
                'attr' => [
                    'placeholder' => 'Enter the minimum salary',
                ],
            ])
            ->add('remote', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class ,[
                'required' => false,
                'label' => 'Remote',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
