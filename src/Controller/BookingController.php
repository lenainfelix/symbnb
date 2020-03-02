<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
     public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();

        $form= $this->createForm(BookingType::class,$booking);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            
            $duration = date_diff($booking->getStartDate(),$booking->getEndDate());

            $booking->setBooker($this->getUser());
            $booking->setAd($ad);

            if (!$booking->isAvailable())
            {
                $this->addFlash( 
                    'warning',
                    "Le logement est occupé a ces dates"
                );
            }

            else{

                $manager->persist($booking);
                $manager->flush();
                    
                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
            
        ]);
    }

    /**
     * permet d'afficher la page d'une reservation
     * 
     * @Route("/booking/{id}", name="booking_show")
     *
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking,Request $request, ObjectManager $manager)
    {
        $comment = new Comment();

        $form= $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);

        
        if($form->isSubmitted()&& $form->isValid()){
            $comment->setAuthor($this->getUser());
            $comment->setAd($booking->getAd());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash( 
                'success',
                "Votre commentaire a bien été pris en compte !"
            );

        }

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
            
        ]);
    }
}
