<?php

namespace Webhines\SymbinBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Webhines\SymbinBundle\Entity\Document;
use Webhines\SymbinBundle\Form\DocumentType;

/**
 * Document controller.
 *
 * @Route("/")
 */
class DocumentController extends Controller
{
    /**
     * Displays a form to create a new Document entity.
     *
     * @Route("new", name="_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Document();
        $form   = $this->createForm(new DocumentType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Document entity.
     *
     * @Route("create", name="_create")
     * @Method("post")
     * @Template("WebhinesSymbinBundle:Document:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Document();
        $request = $this->getRequest();
        $form    = $this->createForm(new DocumentType(), $entity);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('view', array('slug' => $entity->getSlug())));
                
            }
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Document entity.
     *
     * @Route("{slug}/edit", name="_edit")
     * @Template()
     */
    public function editAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WebhinesSymbinBundle:Document')->findOneBySlug($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm = $this->createForm(new DocumentType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Document entity.
     *
     * @Route("{id}/update", name="_update")
     * @Method("post")
     * @Template("WebhinesSymbinBundle:Document:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('WebhinesSymbinBundle:Document')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Document entity.');
        }

        $editForm   = $this->createForm(new DocumentType(), $entity);

        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $editForm->bindRequest($request);

            if ($editForm->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('_edit', array('slug' => $entity->getSlug())));
            }
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

}
