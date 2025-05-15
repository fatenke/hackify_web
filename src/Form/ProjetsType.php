<?php

namespace App\Form;

use App\Entity\Projets;
use App\Entity\Technologies;
use App\Entity\Hackathon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class ProjetsType extends AbstractType
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
            ->add('technologies', EntityType::class, [
                'class'        => Technologies::class,
                'choice_label' => 'nom_tech',
                'multiple'     => true,
                'expanded'     => true,
                'required'     => true,
                'constraints'  => [
                    new Count([
                        'min' => 1,
                        'minMessage' => 'Vous devez sélectionner au moins une technologie.',
                    ]),
                ],
            ])
            ->add('hackathon', EntityType::class, [
                'class'        => Hackathon::class,
                'choice_label' => 'nom_hackathon',
                'required'     => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projets::class,
        ]);
    }
}
