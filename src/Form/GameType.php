<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            /*
            ->add('NumberPlayers', ChoiceType::class,[
              'choices' => [
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
                '6' => 6
              ],
              'expanded' => false,
              'multiple' => false
            ])
            */
            ->add('NumberPlayers', HiddenType::class,[
              'data' => 2
            ])
            ->add('Player1Section', GamePlayerType::class,[
              'mapped' => false
            ])
            ->add('Player2Section', GamePlayerType::class,[
              'mapped' => false
            ])
          ;

          $formModifier = function (FormInterface $form,  $number_players = null) {
            if($number_players){
              for($i = 1; $i < ($number_players+1) && $i <= 6; $i++){
                $form
                ->add('Player' . ($i) . 'Section', GamePlayerType::class,[
                  'mapped' => false,
                  'label' => 'lolomfg'
                ]);
              }
            }
            

          };

          $builder->addEventListener(
              FormEvents::PRE_SET_DATA,
              function (FormEvent $event) use ($formModifier) {
                  $data = $event->getData();

                  $formModifier($event->getForm(), $data->getNumberPlayers());
              }
          );

          $builder->get('NumberPlayers')->addEventListener(
              FormEvents::POST_SUBMIT,
              function (FormEvent $event) use ($formModifier) {

                  $number_players = $event->getForm()->getData();

                  $formModifier($event->getForm()->getParent(), $number_players);
              }
          );
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
