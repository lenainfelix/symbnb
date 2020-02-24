<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Form\RegistrationType;
use App\Form\PasswordChangeType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gerer le formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils )
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username

        ]);
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {

    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        
        
        if($form->isSubmitted()&& $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());

            $user->setHash($hash);


            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash( 
                'success',
                "Votre compte : <strong>{$user->getUsername()}</strong> a bien été créé"
            );
                
                return $this->redirectToRoute('account_login');
            }
            

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le form de profil
     *
     * @Route("/account/profile", name="account_profile")
     * 
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        $user = $this->getUser();

        $form= $this->createForm(ProfileType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            

            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash( 
                'success',
                "Le profil <strong>{$user->getUsername()}</strong> a bien été modifier"
            );
                
                return $this->redirectToRoute('account_index');
            }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

    


        
    }

    /**
     * Permet d'afficher et de gerer le form de changement de mot de passe
     * 
     * @Route("/account/password", name="account_password")
     *
     * @return void
     */
    public function passwordChange(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $form= $this->createForm(PasswordChangeType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());

            $user->setHash($hash);
            

            $manager->persist($user);
            $manager->flush();
            
            $this->addFlash( 
                'success',
                "Le mot de passe a bien été modifié"
            );
                
                return $this->redirectToRoute('account_index');
            }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * 
     * @Route("/account", name="account_index")
     *
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }
}
