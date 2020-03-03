<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{currentPage}", name="admin_bookings_index", requirements={"currentPage":"\d+"})
     */
    public function index(BookingRepository $repo, $currentPage = 1,PaginationService $pagination)
    {
        
        $pagination->setEntityClass(Booking::class)
                    ->setCurrentPage($currentPage);
                    

       

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination,
         
        ]);
    }

    /**
     * Permet d'editer une resa
     * 
     *  @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(AdminBookingType::class,$booking);

        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $booking->setAmount(0);
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "la reservation n° {$booking->getId()} a bien été modifiée"
            );

            return $this->redirectToRoute("admin_bookings_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * Permet de supprimer une resa
     * 
     * @Route("/admin/booking/{id}/delete", name="admin_bookings_delete")
     *
     * @return Response
     */
    public function delete(Booking $booking, ObjectManager $manager, Request $request)
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );
        return $this->redirectToRoute("admin_bookings_index");
    }
}
