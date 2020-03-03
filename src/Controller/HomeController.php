<?php 

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller{

    /**
     * @Route("/", name="homepage")
     *
     * @return void
     */
    public function home(AdRepository $adRepo,UserRepository $userRepo){

        

        return $this->render('home.html.twig',[
            'bestAds' => $adRepo->findBestAds(3),
            'bestUsers' => $userRepo->findBestUsers(2)
        ]);
    }


}

?>