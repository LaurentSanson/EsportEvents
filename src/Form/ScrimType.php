<?php

namespace App\Form;

use App\Entity\Platform;
use App\Entity\Scrim;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ScrimType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('logo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                    ])
                ]
            ])
            ->add('scrimDate', DateTimeType::class, [
                'label' => 'Date of your Scrim',
                'widget' => 'single_text'])
            ->add('scrimlimitRegistrationDate', DateTimeType::class, [
                'label' => 'Deadline for registration',
                'widget' => 'single_text'])
            ->add('platform', EntityType::class, [
                'class' => Platform::class,
                'choice_label' => 'name'
            ])
            ->add('game', SearchType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Scrim::class,
        ]);
    }
}
