<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of StatutCommandeController
 * @Route("/administration/statuts")
 * @author abdelhak
 */
class StatutCommandeController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_statuts")
     */
    public function indexAction() {
        $statuts = $this->em->getRepository("AppBundle:StatutCommande")->findAll();
        $statut = new \AppBundle\Entity\StatutCommande();
        $form = $this->createForm(\AppBundle\Form\StatutCommandeType::class, $statut);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($statut);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Statuts ajouté avec succès !');
                return $this->redirectToRoute("app_admin_statuts");
            }
        }
        return $this->render("AppBundle:admin:statuts.html.twig", array(
                    "statuts" => $statuts,
                    "formStatut" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_statuts_remove")
     */
    public function removeAction($id) {
        $statut = $this->em->getRepository("AppBundle:StatutCommande")->find($id);
        if ($statut) {
            if (count($statut->getCommandes()) > 0)
                $this->request->getSession()->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer un statut rattaché à des commandes !');
            else {
                $this->em->remove($statut);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Statut supprimé avec succès !');
            }
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Statut demandé n\'existe pas !');

        return $this->redirectToRoute("app_admin_statuts");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_statuts_edit")
     */
    public function editAction($id) {
        $statut = $this->em->getRepository("AppBundle:StatutCommande")->find($id);
        if (!$statut) {
            $this->request->getSession()->getFlashBag()->add('error', 'Statut demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_statuts");
        }
        $form = $this->createForm(\AppBundle\Form\StatutCommandeType::class, $statut);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Statut modifié avec succès !');
                return $this->redirectToRoute("app_admin_statuts");
            }
        }
        return $this->render("AppBundle:admin:statuts_edit.html.twig", array(
                    "statut" => $statut,
                    "formStatut" => $form->createView()
        ));
    }

}
