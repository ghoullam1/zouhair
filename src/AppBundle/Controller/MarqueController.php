<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of MarqueController
 *  @Route("/administration/marques")
 * @author abdelhak
 */
class MarqueController extends AdvancedController {

    /**
     * @Route("/{page}", name="app_admin_marques")
     */
    public function indexAction($page = 1) {
        $marques = $this->em->getRepository("AppBundle:Marque")->findAll();
        $marque = new \AppBundle\Entity\Marque();
        $form = $this->createForm(\AppBundle\Form\MarqueType::class, $marque);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($marque);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Marque ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_marques");
            }
        }

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
                $marques, $page, 3
        );

        if (($page > $pagination->getPageCount() and $page > 1) or $page < 1)
            throw $this->createNotFoundException("Le contenu demandé n'existe pas !");
        return $this->render("AppBundle:admin:marques.html.twig", array(
                    "pagination" => $pagination,
                    "formMarque" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_marques_remove")
     */
    public function removeAction($id) {
        $marque = $this->em->getRepository("AppBundle:Marque")->find($id);
        if ($marque) {
            if (count($marque->getProduits()) > 0)
                $this->request->getSession()->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer une marque rattaché à des produits !');
            else {
                $this->em->remove($marque);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Marque supprimée avec succès !');
            }
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Marque demandée n\'existe pas !');

        return $this->redirectToRoute("app_admin_marques");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_marques_edit")
     */
    public function editAction($id) {
        $marque = $this->em->getRepository("AppBundle:Marque")->find($id);
        if (!$marque) {
            $this->request->getSession()->getFlashBag()->add('error', 'Marque demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_marques");
        }
        $form = $this->createForm(\AppBundle\Form\MarqueType::class, $marque);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                if ($this->request->request->has("check_image") and $this->request->request->get("check_image") === "true") {
                    if (!is_null($marque->getImage()) and ! is_null($marque->getImage()->getId())) {
                        if (!is_null($marque->getImage()->getFile())) {
                            $marque->getImage()->upload();
                        }
                    }
                }
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Marque modifiée avec succès !');
                return $this->redirectToRoute("app_admin_marques");
            }
        }
        return $this->render("AppBundle:admin:marques_edit.html.twig", array(
                    "marque" => $marque,
                    "formMarque" => $form->createView()
        ));
    }

}
