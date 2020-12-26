<?php

namespace App\Form;

use App\Entity\Deck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('Description')
            ->add('primary_format', EntityType::class, [
                'class' => 'App\Entity\GameFormat'
              ] )
              
            ->add('primary_player', EntityType::class,[
              'class' => 'App\Entity\Player'
            ])

            ->add('colors', EntityType::class,[
              'class' => 'App\Entity\Color',
              'multiple' => true,
              'expanded' => true
            ])
            /*
            ->add('commanders', EntityType::class,[
              'class' => 'App\Entity\Commander',
              'multiple' => true,
              'expanded' => true
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deck::class,
        ]);
    }
}
