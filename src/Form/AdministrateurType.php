<?php

namespace App\Form;

use App\Entity\Administrateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AdministrateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '2',
                        'maxlenght' => '50',
                    ],
                    'label' => 'Nom <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 2, 'max' => 50])
                    ]
                ]

            )
            ->add(
                'prenom',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '2',
                        'maxlenght' => '50',
                    ],
                    'label' => 'Prénom <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 2, 'max' => 50])
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'class' => 'form-control ',
                        'minlenght' => '2',
                        'maxlenght' => '180',
                    ],
                    'label' => 'Adresse email <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                        new Assert\Length(['min' => 2, 'max' => 180])
                    ]
                ]
            )

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Mot de passe <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirmez le mot de passe',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Administrateur::class,
            'is_edit' => false

        ]);
    }
}
