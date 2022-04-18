<?php

/**
 * Ce formulaire est utilisé pour modifier les informations
 * d'un utilisateur prééxistant
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('profile_pic', FileType::class,
                ['data_class' => null,
                    'label' => 'Changer l\'image de profil ? (2Mo max)',
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypesMessage' => 'Seuls les formats d\'images statiques sont acceptés',
                        ])
                    ],])
            ->add('presentation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}