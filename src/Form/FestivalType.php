<?php

namespace App\Form;

use App\Entity\Festival;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('dateStart', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateEnd', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('cancelled')
            ->add('color', TextType::class)
            ->add('user' ,EntityType::class, [
                'class' => User::class,
                'required' => true
            ]) 
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
