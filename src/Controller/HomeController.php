<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller{

    /**
     * @Route("/hello/{prenom}/{age}", name="hello")
     * @Route("/salut", name="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     * Montre la page qui dit bonjour
     *
     * @return void
     */
    public function hello($prenom = 'anonyme', $age=0){
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }

    /**
     * @Route("/", name="homepage")
     *
     * @return void
     */
    public function home(){
        $prenoms =["Jesus"=>26,"Joseph"=>45,"Marie"=>32];

        return $this->render('home.html.twig',
        [
            'title'=>"Bonjour a tous!",
            'age'=>10,
            'tableau'=>$prenoms,

        ]);
    }


}

?>