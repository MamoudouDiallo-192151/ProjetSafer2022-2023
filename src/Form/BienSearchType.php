<?php

namespace App\Form;

use App\Entity\BienSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('maxPrice', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Budget maximale',
                    'class' => 'form-control',
                ]
            ])
            ->add('minSurface', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['placeholder' => 'Surface minimale']
            ])
            ->add('titre', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom du bien'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BienSearch::class,
            //get pour que les personnes puissent partager leurs recherchent
            'method' => 'get',
            'csrf_protectation' => false,
        ]);
    }
    /**
     * permet une utilisation  correcte des parametres  de recherche dans l'url
     *
     * @return void
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
