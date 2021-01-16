<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Psr\Log\LoggerInterface;


use App\Form\GamePlayerType;
use App\Entity\GamePlayer;




class GameType extends AbstractType
{

    private $logger;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('PlayDate')
            ->add('Format')
            ->add('NumberTurns')          
            ->add('GamePlayers', CollectionType::class, [
                'entry_type'   => GamePlayerType::class,
                'label'        => 'List players.',
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype'    => true,
                'required'     => false,
                'by_reference' => false,
                'delete_empty' => true,
                'attr'         => [
                    'class' => 'players-collection',
                ],
            ]
        );

        $builder->add('save', SubmitType::class, [
            'label' => 'Save Game',
        ]);        
        
        


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class
        ]);
    }

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getBlockPrefix()
    {
        return 'GameType';
    }    

}
