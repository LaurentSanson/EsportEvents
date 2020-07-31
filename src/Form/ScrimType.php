<?php

namespace App\Form;

use App\Entity\MatchStyle;
use App\Entity\Platform;
use App\Entity\Scrim;
use App\Form\DataTransformer\GameToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ScrimType extends AbstractType
{
    private $transformer;

    public function __construct(GameToStringTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

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
            ->add('description')
            ->add('scrimDate', DateTimeType::class, [
                'label' => 'Date of your Scrim',
                'widget' => 'single_text'])
            ->add('scrimlimitRegistrationDate', DateTimeType::class, [
                'label' => 'Deadline for registration',
                'widget' => 'single_text'])
            ->add('platform', EntityType::class, [
                'class' => Platform::class,
                'choice_label' => 'name',
                'choice_value' => function (?Platform $entity) {
                    return $entity ? $entity->getName() : '';
                },
            ])
            ->add('game', SearchType::class)
            ->add('matchStyle', EntityType::class, [
                'class' => MatchStyle::class,
                'choice_label' => 'name',
                'choice_value' => function (?MatchStyle $entity) {
                    return $entity ? $entity->getName() : '';
                },
            ]);

        $builder->get('game')
            ->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Scrim::class,
        ]);
    }
}
