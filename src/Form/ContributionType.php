<?php

namespace App\Form;

use App\Entity\Contribution;
use App\Entity\Images;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ContributionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ["label" => "Titre :"])
            ->add('content', TextareaType::class, ["label" => "Récit :", 'attr' => ['class' => 'tinymce']])
            ->add('main_image', FileType::class, ['label' => 'Image principale (optionel)'])
            ->add('image_gallery_1', FileType::class, ['label' => 'Image de gallerie 1 (optionel)', "required" => false])
            ->add('image_gallery_2', FileType::class, ['label' => 'Image de gallerie 2 (optionel)', "required" => false])
            ->add('image_gallery_3', FileType::class, ['label' => 'Image de gallerie 3 (optionel)', "required" => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contribution::class,
        ]);
    }
}
