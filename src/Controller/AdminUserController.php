<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminProfileType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * Permet d'afficher la liste des utilisateurs
     * 
     * @Route("/admin/users", name="admin_users_index")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    /**
     * Permet de modifier un utilisateur
     * 
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     *
     * @param User $user
     * @param ObjectManager $manager
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(AdminProfileType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur a bien été modifier.'
            );

            return $this->redirectToRoute("admin_users_index");
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


    /**
     * Permet de supprimer un utilisateur qui n'a pas d'annonce ni de reservation ni de c
     *
     * @return void
     */
    public function delete()
    {

    }
}
