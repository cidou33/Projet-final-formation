<?php

namespace App\Form;

use App\Entity\Transcriptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranscriptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bandName')
            ->add('songName')
            ->add('pdfFile')
            ->add('mediaLink')
            ->add('active')
            ->add('createDate')
            ->add('updateDate')
            ->add('numbDownload')
            ->add('difficultyLevel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transcriptions::class,
        ]);
    }
}
