<?php

namespace App\Form;

use App\Entity\Hackathon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class HackathonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_hackathon', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom du hackathon'
                ],
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Décrivez votre hackathon'
                ],
                'required' => true
            ])
            ->add('date_debut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('date_fin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('lieu', TextType::class, [
                'attr' => [
                    'placeholder' => 'Lieu du hackathon'
                ],
                'required' => true
            ])
            ->add('theme', TextType::class, [
                'attr' => [
                    'placeholder' => 'Thème du hackathon'
                ],
                'required' => true
            ])
            ->add('max_participants', IntegerType::class, [
                'attr' => [
                    'placeholder' => 'Nombre maximum de participants'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hackathon::class,
        ]);
    }
}
