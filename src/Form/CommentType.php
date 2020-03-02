<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class ,$this->getConfiguration('Note sur 5','Donnez un nombre d\'étoiles à cette expérience',[
                'attr' => [
                    'min'=> 0,
                    'max'=> 5,
                    'step' => 1
                ]
            ]))
            ->add('content',TextareaType::class,$this->getConfiguration('Commentaire','Décrivez votre experience en quelques mots'));
                    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
