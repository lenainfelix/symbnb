<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class PaginationService {

    private $entityClass;

    private $limit = 10;

    private $currentPage = 1;

    private $manager;

    private $routeName;

    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->templatePath = $templatePath;
        $this->route   = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig    = $twig;
    }

    public function display()
    {
        $this->twig->display($this->templatePath,[
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'routeName' => $this->route

        ]);
    }

    public function getData()
    {
        if (empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer!
            Utiliser la methode setEntityClass() de votre objet pagination service" );
        }
        //1 calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        
        //2 demander au repo de trouver les elements
        $repo =$this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([],[],$this->limit, $offset);
        //3 renvoyer l'elements
        
        return $data;
    }
    
    public function getPages()
    {
        if (empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer!
            Utiliser la methode setEntityClass() de votre objet pagination service" );
        }
        //1 connaitre le total des enregistrement
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        //2 faire connaitre la division l'arrondi et le renvoyer
        $pages = ceil($total/ $this->limit);
        return $pages;
    }

    /**
     * Get the value of entityClass
     */ 
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Set the value of entityClass
     *
     * @return  self
     */ 
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Get the value of limit
     */ 
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     *
     * @return  self
     */ 
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of currentPage
     */ 
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the value of currentPage
     *
     * @return  self
     */ 
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Get the value of routeName
     */ 
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set the value of routeName
     *
     * @return  self
     */ 
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * Get the value of templatePath
     */ 
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Set the value of templatePath
     *
     * @return  self
     */ 
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;

        return $this;
    }
}