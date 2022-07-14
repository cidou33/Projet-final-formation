<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Trainings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre : ',
                'attr' => [
                    'placeholder' => 'Écrivez un titre'
                ]
            ])
            ->add('Content', TextareaType::class, [
                'label' => 'Description : ',
                'attr' => [
                    'placeholder' => 'Écrivez une description',
                    'cols' => '30',
                    'rows' => '5'
                ]
            ])
            //->add('createDate')
            //->add('updateDate')
            //->add('active')
            ->add('category', EntityType::class, [
                'label' => 'Choisissez une catégorie : ',
                'choice_label' => 'name',
                'class' => Categories::class
            ])
            ->add('pdfFile', FileType::class, [
                'label' => 'Choisissez un fichier pdf : ',
                'data_class' => null,
                'required' => false
            ])
            //->add('numbDownload')
            //->add('liked', CheckboxType::class)
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trainings::class,
        ]);
    }
}
