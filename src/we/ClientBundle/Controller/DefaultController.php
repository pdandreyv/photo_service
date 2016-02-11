<?php

namespace we\ClientBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    private function getEm(){
        return $this->getDoctrine()->getEntityManager();
    }
    
    public function indexAction($page=1)
    {
        $photos = $this->getEm()->getRepository('weClientBundle:Photo')->findAll();
        
        $pagination = $this->get('pagination')
            ->setParams(array(
                'currentPage' => $page,
                'recordsTotal' => count($photos),
                'link' => $this->generateUrl('page_show1')
            ))
            ->get();

        $photos = array_slice($photos, $pagination['start'], $pagination['records']);

        return $this->render('weClientBundle:Default:index.html.twig',array(
            'search'=>0,
            'pagination' => $pagination,
            'photos'=>$photos
        ));
    }
    public function find1Action($search,$page=1)
    {
        $photos = $this->getEm()->getRepository('weClientBundle:Tag')->getSearch($search);

        $pagination = $this->get('pagination')
            ->setParams(array(
                'currentPage' => $page,
                'recordsTotal' => count($photos),
                'link' => $this->generateUrl('find_photo1',array('search'=>$search))
            ))
            ->get();

        $photos = array_slice($photos, $pagination['start'], $pagination['records']);
        $ph = array();
        foreach($photos as $p){
            $ph[] = $this->getEm()->getRepository('weClientBundle:Photo')->find($p['id']);
        }
        return $this->render('weClientBundle:Default:index.html.twig',array(
            'search'=>$search,
            'pagination' => $pagination,
            'photos'=>$ph
        ));
    }
    public function findAction(Request $request)
    {
        $post = $request->request->all();
        $search = $post['search'];
        return $this->find1Action($search,1);
    }
}
