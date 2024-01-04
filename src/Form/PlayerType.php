<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,  [
                'label' => 'Entrer le nom du Player',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message'=> 'Le nom du Player ne peut pas être vide']),
                    new Length([
                        'min' => 1,
                        'max' => 20,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('mana', IntegerType::class,  [
                'label' => 'Entrer le mana du Player',
                'required' => true,
            ])
            ->add('ad', IntegerType::class,  [
                'label' => 'Entrer l\'AD du Player',
                'required' => true,
            ])
            ->add('ap', IntegerType::class,  [
                'label' => 'Entrer l\'AP nom du Player',
                'required' => true,
            ])
            ->add('pv', IntegerType::class,  [
                'label' => 'Entrer les PV du Player',
                'required' => true,
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
