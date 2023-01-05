<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UtilisateurProfileType extends AbstractType
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
                        'minlength' => '2',
                        'maxlength' => '50',
                    ],
                    'label' => false,
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
                        'minlength' => '2',
                        'maxlength' => '50',
                    ],
                    'label' => false,
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
                    'disabled' => $options['is_edit'], 'attr' => [
                        'class' => 'form-control',
                    ],   'label' => false,
                ],
                [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    ]
                ]
            )
            ->add(
                'numeroTelephone',
                NumberType::class,

                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control',
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
                        'minlength' => '2',
                        'maxlength' => '50',
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
            )
            ->add('imageFile', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => true,
                'required' => false,
                'label'    => 'Ajouter une photo',


            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'is_edit' => false
        ]);
    }
}
