<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of DealController
 * @Route("/administration/deals")
 * @author abdelhak
 */
class DealController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_deals")
     */
    function indexAction() {
        $deals = $this->em->getRepository("AppBundle:Deal")->findAll();
        $deal = new \AppBundle\Entity\Deal();
        $form = $this->createForm(\AppBundle\Form\DealType::class, $deal);
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_deal")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($deal);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Deal ajouté avec succès !');
                return $this->redirectToRoute("app_admin_deals");
            }
        }
        $page = $this->request->query->getInt("page", 1);
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
                $deals, $page, 5
        );

        if (($page > $pagination->getPageCount() and $pagination->getPageCount() > 0) or $page < 1)
            throw $this->createNotFoundException("Le contenu demandé n'existe pas !");
        return $this->render("AppBundle:admin:deals.html.twig", array(
                    "pagination" => $pagination,
                    "formDeal" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_deals_remove")
     */
    public function removeAction($id) {
        $deal = $this->em->getRepository("AppBundle:Deal")->find($id);
        if ($deal) {
            $this->em->remove($deal);
            $this->em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'Deal supprimé avec succès !');
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Deal demandé n\'existe pas !');

        return $this->redirectToRoute("app_admin_deals");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_deals_edit")
     */
    public function editAction($id) {
        $deal = $this->em->getRepository("AppBundle:Deal")->find($id);
        if (!$deal) {
            $this->request->getSession()->getFlashBag()->add('error', 'Deal demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_deals");
        }
        $form = $this->createForm(\AppBundle\Form\DealType::class, $deal);
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_deal")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                if ($this->request->request->has("check_image") and $this->request->request->get("check_image") === "true") {
                    $deal->getImage()->upload();
                }
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Deal modifié avec succès !');
                return $this->redirectToRoute("app_admin_deals");
            }
        }
        return $this->render("AppBundle:admin:deals_edit.html.twig", array(
                    "deal" => $deal,
                    "formDeal" => $form->createView()
        ));
    }

}
