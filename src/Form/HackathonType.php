<?php

namespace App\Form;

use App\Entity\Hackathon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HackathonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('id_organisateur')*/
            ->add('nom_hackathon', null, [
                'property_path' => 'nomHackathon',
            ])
            ->add('description')
            ->add('date_debut', null, [
                'widget' => 'single_text',
                'property_path' => 'dateDebut',
            ])
            ->add('date_fin', null, [
                'widget' => 'single_text',
                'property_path' => 'dateFin',
            ])
            ->add('lieu')
            ->add('theme')
            ->add('max_participants', null, [
                'property_path' => 'maxParticipants',
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
