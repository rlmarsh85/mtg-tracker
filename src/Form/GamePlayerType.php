<?php

namespace App\Form;

use App\Entity\GamePlayer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use App\Repository\DeckRepository;

class GamePlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('Player', EntityType::class,[
              'class' => 'App\Entity\Player'
            ])

            ->add('Deck', EntityType::class,[
              'class' => 'App\Entity\Deck',
              'query_builder' => function (DeckRepository $er) {
                return $er->createQueryBuilder('d')
                    ->orderBy('d.name', 'ASC');
                }              
            ])

            ->add('WinningPlayer', CheckboxType ::class, [
              'required' => false,
              'row_attr' => ['class' => 'form-field']
            ])
            
            ->add('FirstOrSecondTurnSolRing', CheckboxType ::class, [
              'required' => false,
              'label' => 'Starting Sol Ring',
              'row_attr' => ['class' => 'form-field']
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
