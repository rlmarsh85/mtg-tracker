<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Psr\Log\LoggerInterface;

use App\Form\GamePlayerType;




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
              'entry_type' => GamePlayerType::class,       
              'entry_options' => ['label' => false],
              'allow_add' => true,
              'by_reference' => false,                                              
            ]);

          ;


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

}
