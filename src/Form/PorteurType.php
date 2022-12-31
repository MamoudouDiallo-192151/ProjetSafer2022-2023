<?php

namespace App\Form;

use App\Entity\Porteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints as Assert;

class PorteurType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
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
            ])
            ->add(
                'numeroTelephone',
                NumberType::class,

                [
                    "label"  => 'Numéro de téléphone <span class="text-danger">*</span>',
                    'label_html' => true,
                    'attr' => [
                        'placeholder' => "Ex: 58679068",
                    ],
                    'constraints' => [
                        new Assert\NotBlank(['message' => "Veuillez entrez un numero valide"]),
                        new Assert\Regex([
                            'pattern' => '/^((\+|00)216)?([2579][0-9]{7}|(3[012]|4[01]|8[ 0128])[0-9]{6}|42[16][0-9]{5})$/',
                            'match'   => true,
                            'message' => "S'il vous plaît entrer un numéro de téléphone valide en Tunisie.",
                        ]),
                    ],
                    "required"  => false,


                ]

            )
            ->add(
                'adresse',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '2',
                        'maxlenght' => '50',
                    ],
                    'label' => 'Adresse',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 2, 'max' => 100])
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Porteur::class,
        ]);
    }
}
