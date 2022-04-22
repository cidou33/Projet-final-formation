<?php

namespace App\Form;

use App\Entity\Difficulty;
use App\Entity\Transcriptions;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranscriptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bandName', TextType::class, [
                'label' => 'Groupe',
                'attr' => [
                    'placeholder' => 'Écrivez le nom du groupe'
                ]
            ])
            ->add('songName', TextType::class, [
                'label' => 'Chanson',
                'attr' => [
                    'placeholder' => 'Écrivez le titre de la chanson'
                    ]
                ])
            ->add('pdfFile', FileType::class, [
                'label' => 'Choisissez un fichier pdf',
                'data_class' => null
            ])
            ->add('mediaLink', TextType::class, [
                'label' => 'Lien youtube',
                'attr' => [
                    'placeholder' => 'Écrivez le lien vers la vidéo youtube'
                ]
            ])
            //->add('active')
            //->add('createDate')
            //->add('updateDate')
            //->add('numbDownload')
            ->add('difficultyLevel', EntityType::class, [
                'label' => 'Choisissez la difficulté',
                'choice_label' => "name",
                'class' => Difficulty::class
            ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transcriptions::class,
        ]);
    }
}
