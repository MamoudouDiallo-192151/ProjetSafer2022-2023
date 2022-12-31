<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('titre')
            ->add('ville')
            ->add('codePostal')
            ->add('description')
            ->add('surface')
            ->add('prix')
            ->add('statusBien', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'designation'
            ])
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
