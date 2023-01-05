<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = array(
            "Blog" => "ROLE_BLOG",
            "Client" => "ROLE_CLIENT",
            "Porteur" => "ROLE_PORTEUR",
            "Commercial" => "ROLE_COMMERCIAL",
            "Staff" => "ROLE_PERSONNEL",
            "Propiétaire" => "ROLE_PROPRIETAIRE",
            "Admin" => "ROLE_ADMIN",
            "SuperAdmin" => "ROLE_SUPERADMIN",
        );
        $builder
            ->add('roles', ChoiceType::class, array(
                'attr' => ['class' => ' form-select form-select-lg mb-3'],
                'choices'  => $roles,
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
                'label' => 'Rôles',
                'translation_domain' => 'messages'
            )) //mettre multiple à true si on veut pouvoir enregistrer plusieurs rôles pour un membre
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-2'],
                'label' => 'Enrégister'
            ]);
        /**
         * mettre ceci si le multiple est à false
         *  ->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ))
         */
    }
    /**
     * Undocumented function
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'is_edit' => false
        ]);
    }
}
