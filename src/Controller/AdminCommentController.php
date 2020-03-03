<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{currentPage}", name="admin_comments_index", requirements={"currentPage":"\d+"})
     */
    public function index(CommentRepository $repo, $currentPage =1, PaginationService $pagination)
    {
        
        $pagination ->setEntityClass(Comment::class)
                    ->setCurrentPage($currentPage);


        return $this->render('admin/comment/index.html.twig', [
        'pagination' => $pagination
    ]);
    }

    /**
     * permet de modifier un commentaire
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comments_edit")
     *
     * 
     * @return void
     */
    public function edit(Comment $comment, ObjectManager $manager, Request $request)
    {

        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

    

        if ($form->isSubmitted()&& $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();
        }

        $this->addFlash( 
            'success',
            "Le commentaire a bien été modifée"
        );

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
            ]
        );

    }

    /**
     * permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comments_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return void
     */
    public function delete(Comment $comment, ObjectManager $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash( 
            'success',
            "Le commentaire a bien été supprimer"
        );
        

        return $this->redirectToRoute("admin_comments_index");

    }
}
