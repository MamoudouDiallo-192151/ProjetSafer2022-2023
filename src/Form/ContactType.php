<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ContactType extends AbstractType
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
                        'maxlenght' => '255',
                    ],
                    'label' => 'Nom <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\Length(['min' => 2, 'max' => 255])
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
                        'maxlenght' => '255',
                    ],
                    'label' => 'Prénom <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\Length(['min' => 2, 'max' => 255])
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
                        'maxlenght' => '255',
                    ],
                    'label' => 'Adresse email <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add(
                'numeroTelephone',
                NumberType::class,

                [
                    "label"  => 'Téléphone <span class="text-danger">*</span>',
                    'label_html' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => "Ex: 0753181404",
                    ],
                    'constraints' => [
                        new Assert\Regex([
                            'pattern' => '/^((\+|00)33)?([2579][0-9]{7}|(3[012]|4[01]|8[ 0128])[0-9]{6}|42[16][0-9]{5})$/',
                            'match'   => true,
                            'message' => "S'il vous plaît entrer un numéro de téléphone valide en France.",
                        ]),
                    ],
                    "required"  => false,


                ]

            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '5',
                        'maxlenght' => '255',
                    ],
                    'label' => 'Message',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
