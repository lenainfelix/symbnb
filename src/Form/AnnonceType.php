<?php

namespace App\Form;

use App\Entity\Ad;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class AnnonceType extends ApplicationType
{
    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre','Tapez votre titre'))
            ->add('slug', TextType::class, $this->getConfiguration('Adresse web','Tapez l\'adresse web (automatique)',['required'=>false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration('URL de l\'image principale','Donnez l\'adresse d\'une image qui fait rever'))
            ->add('introduction', TextType::class,$this->getConfiguration('Introduction','Décrivez votre annonce en quelques mots'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description','Donnez une description qui donne envie de louer votre bien.'))
            ->add('rooms', IntegerType::class ,$this->getConfiguration('Nombre de chambres','Donnez le nombre de chambre disponibles'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix','Entrez le prix de votre location pour une nuit'))
            ->add('images', CollectionType::class,[

                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}