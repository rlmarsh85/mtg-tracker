<?php

namespace App\Form;

use App\Entity\Commander;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommanderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name')
            ->add('Scryfall_URL',TextType::class,[
              'label' => 'Scryfall URl'
            ])
            ->add('colors', EntityType::class,[
              'class' => 'App\Entity\Color',
              'multiple' => true,
              'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commander::class,
        ]);
    }
}
