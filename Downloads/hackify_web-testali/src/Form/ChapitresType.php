<?php

namespace App\Form;

use App\Entity\Chapitres;
use App\Entity\Ressources;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ChapitresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('urlFichier')      // ✅ correspond à getUrlFichier()
        ->add('titre')           // ✅ OK
        ->add('contenu')         // ✅ OK
        ->add('formatFichier')   // ✅ correspond à getFormatFichier()
        ->add('id_ressources', EntityType::class, [
            'class' => Ressources::class,
            'choice_label' => 'id',
        ]);
       
        
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chapitres::class,
        ]);
    }
}
