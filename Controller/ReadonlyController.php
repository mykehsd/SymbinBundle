<?php

namespace Webhines\SymbinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Webhines\SymbinBundle\Entity\Document;

class ReadonlyController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('WebhinesSymbinBundle:Readonly:index.html.twig');
    }

    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction ()
    {
        $q = $this->getRequest()->query->get('q');
        
        $documents = $this->getDoctrine()
            ->getRepository('WebhinesSymbinBundle:Document')
            ->search($q);

        return $this->render('WebhinesSymbinBundle:Readonly:search.html.twig', array('documents' => $documents));
    }


    /**
     * @Route("/{slug}", name="view")
     * @Template()
     */
    public function viewAction($slug) 
    {
        $em = $this->getDoctrine()->getEntityManager();
        $document = $em->getRepository('WebhinesSymbinBundle:Document')->findOneBySlug($slug);
        
        if (!$document) {
            throw $this->createNotFoundException('No snippit found');
        }
        
        return $this->render('WebhinesSymbinBundle:Readonly:view.html.twig', array('document' => $document));
    }

}
