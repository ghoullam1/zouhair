<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of VilleController
 * @Route("/administration/modesLivraison")
 * @author abdelhak
 */
class ModeLivraisonController extends AdvancedController {

    /**
     * @Route("/{page}", name="app_admin_modes_livraison")
     */
    public function indexAction($page = 1) {
        $modes = $this->em->getRepository("AppBundle:ModeLivraison")->findAll();
        $mode = new \AppBundle\Entity\ModeLivraison();
        $form = $this->createForm(\AppBundle\Form\ModeLivraisonType::class, $mode);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($mode);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Mode de livraison ajouté avec succès !');
                return $this->redirectToRoute("app_admin_modes_livraison");
            }
        }
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $modes, $page, 3
        );
        if (($page > $pagination->getPageCount() and $page > 1) or $page < 1)
            throw $this->createNotFoundException("Le contenu demandé n'existe pas !");
        return $this->render("AppBundle:admin:modes_livraison.html.twig", array(
                    "pagination" => $pagination,
                    "formMode" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_modes_livraison_remove")
     */
    public function removeAction($id) {
        $mode = $this->em->getRepository("AppBundle:ModeLivraison")->find($id);
        if ($mode) {
            if (count($mode->getCommandes()) > 0)
                $this->request->getSession()->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer un mode de livraison rattaché à des commandes !');
            else {
                $this->em->remove($mode);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Mode de livraison supprimée avec succès !');
            }
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Mode de livraison demandée n\'existe pas !');

        return $this->redirectToRoute("app_admin_modes_livraison");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_modes_livraison_edit")
     */
    public function editAction($id) {
        $mode = $this->em->getRepository("AppBundle:ModeLivraison")->find($id);
        if (!$mode) {
            $this->request->getSession()->getFlashBag()->add('error', 'Mode de livraison demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_modes_livraison");
        }
        $form = $this->createForm(\AppBundle\Form\ModeLivraisonType::class, $mode);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Mode de livraison modifiée avec succès !');
                return $this->redirectToRoute("app_admin_modes_livraison");
            }
        }
        return $this->render("AppBundle:admin:modes_livraison_edit.html.twig", array(
                    "mode" => $mode,
                    "formMode" => $form->createView()
        ));
    }

}
