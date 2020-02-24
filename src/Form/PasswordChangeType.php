<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordChangeType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un bon ot de passe..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmer votre mot de passe", "Confirmer votre mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
