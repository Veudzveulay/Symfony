<?php

namespace App\Form;

use App\Entity\Pet;
use App\Entity\Player;
use App\Entity\race;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('xp')
            ->add('niveau')
            ->add('ad')
            ->add('ap')
            ->add('mana')
            ->add('pv')
            ->add('race', EntityType::class, [
                'class' => race::class,
                'choice_label' => 'nom',
            ])
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'choice_label' => 'name',
                'label' => 'Nom du propriétaire du pet'            
            ])
            ->add ('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}
