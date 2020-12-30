<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PlayerRepository;
use App\Entity\Player;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Form\GamePlayerType;
use Psr\Log\LoggerInterface;

use Symfony\Component\Form\FormInterface;

class GameType extends AbstractType
{

    private $manager;
    private $logger;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('PlayDate')
            ->add('Format')
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
            ->add('Player1Section', GamePlayerType::class,[
              'mapped' => false
            ])
            ->add('Player2Section', GamePlayerType::class,[
              'mapped' => false
            ])
          ;

          $formModifier = function (FormInterface $form,  $number_players = null) {

            for($i = 0; $i < $number_players; $i++){
              $form
              ->add('Player' . ($i+1) . 'Section', GamePlayerType::class,[
                'mapped' => false
              ]);
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
                  // It's important here to fetch $event->getForm()->getData(), as
                  // $event->getData() will get you the client data (that is, the ID)
                  $number_players = $event->getForm()->getData();

                  // since we've added the listener to the child, we'll have to pass on
                  // the parent to the callback functions!
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

    public function __construct(EntityManagerInterface  $objectManager, LoggerInterface $logger)
    {
        $this->manager = $objectManager;
        $this->logger = $logger;
    }

}
