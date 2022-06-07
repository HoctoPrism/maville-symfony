<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('username')
            ->add('roles', ChoiceType::class, [
                'choices'   => [
                    'ROLE_ADMIN'   => 'ROLE_ADMIN',
                    'ROLE_USER'      => 'ROLE_USER',
                    'ROLE_FIRSTCONNECTION'=>'ROLE_FIRSTCONNECTION',
                ],
                'expanded' => true,
                'multiple'  => true,
            ])
            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
}
