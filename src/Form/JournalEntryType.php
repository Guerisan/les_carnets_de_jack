<?php

namespace App\Form;

use App\Entity\JournalEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class JournalEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ["label" => "Titre :"])
            ->add('date', TextType::class, ["label" => "Date :"])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'tinymce']])
            ->add('tags', TextType::class, ["required" => false])
            ->add('illustration', FileType::class,
                ['data_class' => null,
                    'label' => 'Illustration principale',
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypesMessage' => 'Seuls les formats d\'images statiques sont acceptés',
                        ])
                    ],]);

        $builder->get('tags')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(';', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(';', $tagsAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JournalEntry::class,
        ]);
    }
}
