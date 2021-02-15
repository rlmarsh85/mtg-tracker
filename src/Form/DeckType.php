<?php

namespace App\Form;

use App\Entity\Deck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DeckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
              'attr' => ['class' => 'form-control']
            ])
            ->add('Description', TextType::class, [
              'attr' => ['class' => 'form-control']
            ])
            ->add('primary_format', EntityType::class, [
                'class' => 'App\Entity\GameFormat',
                'attr' => ['class' => 'form-control']
              ] )

            ->add('primary_player', EntityType::class,[
              'class' => 'App\Entity\Player',
              'attr' => ['class' => 'form-control']
            ])

            ->add('colors', EntityType::class,[
              'class' => 'App\Entity\Color',
              'multiple' => true,
              'expanded' => true
            ])

            ->add('commanders', EntityType::class,[
              'class' => 'App\Entity\Commander',
              'multiple' => true,
              'expanded' => false,
              'label' => 'Commander(s)',
              'attr' => ['class' => 'form-control']
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Deck::class,
        ]);
    }
}
