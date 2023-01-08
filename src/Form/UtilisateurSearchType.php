<?php

namespace App\Form;

use App\Entity\UtilisateurSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUtilisateur', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UtilisateurSearch::class,
            //get pour que les personnes puissent partager leurs recherchent
            'method' => 'get',
            'csrf_protectation' => false,
        ]);
    }
    /**
     * permet une utilisation  correcte des parametres  de recherch dans l'url
     *
     * @return void
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
