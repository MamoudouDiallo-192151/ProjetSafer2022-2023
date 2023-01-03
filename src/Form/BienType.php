<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'reference',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '3',
                        'maxlenght' => '15',
                    ],
                    'label' => 'Référence <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 3, 'max' => 15])
                    ]
                ]

            )
            ->add(
                'titre',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '3',
                        'maxlenght' => '100',
                    ],
                    'label' => 'Titre <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 3, 'max' => 100])
                    ]
                ]

            )
            ->add(
                'localisation',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlenght' => '3',
                        'maxlenght' => '100',
                    ],
                    'label' => 'Localisation <span class="text-danger">*</span>',
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 3, 'max' => 100])
                    ]
                ]

            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => '5',
                        'maxlength' => '100000',
                    ],
                    'label' => 'Description',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 5, 'max' => 100000])
                    ]
                ]
            )
            ->add('surface', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '500',
                    'maxlength' => '100000',
                ],
            ])
            ->add('prix', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '500',
                    'maxlength' => '100000',
                ],
            ])
            ->add('statusBien', ChoiceType::class, [

                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '500',
                    'maxlength' => '100000',
                ],
                'choices' => $this->getChoices()
            ])
            ->add(
                'categorie',
                EntityType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'class' => Categorie::class,
                    'choice_label' => 'designation',
                    'choice_value' => 'designation',
                    'multiple' => false,
                    'required' => true,
                    'expanded' => false
                ]
            )
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label'    => 'Ajouter une image',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }

    public function getChoices()
    {
        $choices = Bien::STATUSBIEN;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return  $output;
    }
}
