<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     * 
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findAll(),
        ]);
    }


    /**
     * Permet d'afficher le formulaire de modification d'une annonces
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return void
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);
        

        if ($form->isSubmitted()&& $form->isValid())
        {
            $manager->persist($ad);
            $manager->flush();
        }

        $this->addFlash( 
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été modifée"
        );

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
            ]
        );
    }

     /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     *  
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad,ObjectManager $manager)
    {
        if (count($ad->getBookings()) > 0 )
        {
            $this->addFlash(
                'warning', 
                "Vous ne pouvez supprimer une annonce qui a des reservations"
            );
        }
        else{

            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash( 
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimer"
            );
        }

        return $this->redirectToRoute("admin_ads_index");

    }
}


