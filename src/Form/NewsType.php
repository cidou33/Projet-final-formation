<?php

namespace App\Form;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Écrivez un titre'
                ]
            ])
            ->add('Content', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Écrivez votre article'
                ]
            ])
            //->add('createDate')
            //->add('updateDate')
            //->add('active')
            ->add('imgNews', FileType::class, [
                'label' => 'Choisissez une image',
                'data_class' => null,
                'required' => false
            ])
            ->add('imgAlt', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez votre image'
                ]
            ])
            //->add('liked')
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
