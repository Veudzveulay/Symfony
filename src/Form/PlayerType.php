<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,  [
                'label' => 'Entrer le nom du Player',
            ])
            ->add('mana', IntegerType::class,  [
                'label' => 'Entrer le mana du Player',
            ])
            ->add('ad', IntegerType::class,  [
                'label' => 'Entrer l\'AD du Player',
            ])
            ->add('ap', IntegerType::class,  [
                'label' => 'Entrer l\'AP nom du Player',
            ])
            ->add('pv', IntegerType::class,  [
                'label' => 'Entrer les PV du Player',
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
