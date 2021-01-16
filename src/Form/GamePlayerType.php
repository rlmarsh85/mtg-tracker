<?php

namespace App\Form;

use App\Entity\GamePlayer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class GamePlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('Player', EntityType::class,[
              'class' => 'App\Entity\Player'
            ])

            ->add('Deck', EntityType::class,[
              'class' => 'App\Entity\Deck'
            ])

            ->add('WinningPlayer', CheckboxType ::class, [
              'required' => false
            ])
            
            ->add('FirstOrSecondTurnSolRing', CheckboxType ::class, [
              'required' => false,
              'label' => 'Starting Sol Ring'
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GamePlayer::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'GamePlayerType';
    }

}
