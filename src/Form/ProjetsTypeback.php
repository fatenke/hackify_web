<?php

namespace App\Form;

use App\Entity\Hackathon;
use App\Entity\Projets;
use App\Entity\Technologies;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetsTypeback extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'empty_data' => '',
            ])
            ->add('description', TextType::class, [
                'required' => true,
                'empty_data' => '',
            ])
            ->add('ressource', TextType::class, [
                'required' => true,
                'empty_data' => '',
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En cours'  => 'en cours',
                    'En pause'  => 'en pause',
                    'Terminée'  => 'terminee',
                ],
                'placeholder' => 'Choisissez un statut',
            ])
            ->add('priorite', ChoiceType::class, [
                'choices' => [
                    'Faible'  => 'faible',
                    'Moyenne' => 'moyenne',
                    'Haute'   => 'haute',
                ],
                'placeholder' => 'Choisissez une priorité',
            ])
            ->add('technologies', EntityType::class, [
                'class' => Technologies::class,
                'choice_label' => 'nom_tech',
                'multiple' => true,
                'expanded' => true, // render as checkboxes
            ])
            ->add('id_hack', EntityType::class, [
                'class' => Hackathon::class,
                'choice_label' => 'nom_hackathon',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
