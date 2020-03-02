<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('startDate', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date d\'arrivée'
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ'
        ],$this->getConfiguration("Date de départ", "Renseigner une date"))
        ->add('comment', TextareaType::class, $this->getConfiguration("Commentaire", "Si vous avez un commentaire ....", ['required' => false]))
        ->add('booker', EntityType::class,[
            'class' => User::class,
            'label' => 'Réservé par',
            'choice_label' => function($user){
                return $user->getFirstName() . ' ' . strtoupper( $user->getLastName() ); 
            },

        ])
        ->add('ad', EntityType::class,[
            'class' => Ad::class,
            'label' => 'Annonce réservée',
            'choice_label' => function($ad){
                return $ad->getTitle();
            },

        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
