<?php

namespace we\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    private function getEm(){
        return $this->getDoctrine()->getEntityManager();
    }
    
    public function indexAction($page=null)
    {
        $count_item_on_page = 10;
        if(!$page) $page = 1;
        
        $photos = $this->getEm()->getRepository('weClientBundle:Photo')->findAll();

        return $this->render('weClientBundle:Default:index.html.twig',array(
            'page'=>$page,
            'photos'=>$photos
        ));
    }
    
    public function findAction($search)
    {
        $photos = $this->getEm()->getRepository('weClientBundle:Photo')->getSearch($search);

        return $this->render('weClientBundle:Default:index.html.twig',array(
            'page'=>$page,
            'photos'=>$photos
        ));
    }
}
