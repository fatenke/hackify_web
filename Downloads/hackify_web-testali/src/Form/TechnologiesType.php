<?php

namespace App\Form;

use App\Entity\Projets;
use App\Entity\Technologies;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnologiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_tech', TextType::class, [
                'required' => true,
                'empty_data' => '',
            ])
            ->add('type_tech', ChoiceType::class, [
                'choices'  => [
                    'Dev'       => 'dev',
                    'Business'  => 'business',
                    'Finance'   => 'finance',
                    'Securite'  => 'securite',
                    'Reseaux'   => 'reseaux',
                    'Autre'     => 'autre',
                ],
                'placeholder' => 'Choisissez un type',
            ])

            ->add('complexite', ChoiceType::class, [
                'choices'  => [
                    'Haute'    => 'haute',
                    'Moyenne'  => 'moyenne',
                    'Faible'   => 'faible',
                ],
                'placeholder' => 'Choisissez la complexité',
            ])
            ->add('documentaire', TextType::class, [
                'required' => true,
                'empty_data' => '',
            ])
            ->add('compatibilite', ChoiceType::class, [
                'choices'  => [
                    'Windows' => 'windows',
                    'Macos'   => 'macos',
                    'Linux'   => 'linux',
                ],
                'placeholder' => 'Choisissez la compatibilité',
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technologies::class,
        ]);
    }
}
