<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'designation',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => '5',
                        'maxlength' => '100',
                    ],
                    'label' => 'Description',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 5, 'max' => 100])
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
