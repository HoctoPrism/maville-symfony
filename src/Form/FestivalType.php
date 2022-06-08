<?php

namespace App\Form;

use App\Entity\Festival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('type', TextType::class)
            ->add('image', TextType::class)
            ->add('date_start', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_end', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('cancelled')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
