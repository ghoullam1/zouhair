<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of TailleController
 * @Route("/administration/tailles")
 * @author abdelhak
 */
class TailleController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_tailles")
     */
    public function indexAction() {
        $tailles = $this->em->getRepository("AppBundle:Taille")->findAll();
        $taille = new \AppBundle\Entity\Taille();
        $form = $this->createForm(\AppBundle\Form\TailleType::class, $taille);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($taille);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Taille ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_tailles");
            }
        }
        return $this->render("AppBundle:admin:tailles.html.twig", array(
                    "tailles" => $tailles,
                    "formTaille" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_tailles_remove")
     */
    public function removeAction($id) {
        $taille = $this->em->getRepository("AppBundle:Taille")->find($id);
        if ($taille) {
            $this->em->remove($taille);
            $this->em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'Taille supprimée avec succès !');
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Taille demandée n\'existe pas !');

        return $this->redirectToRoute("app_admin_tailles");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_tailles_edit")
     */
    public function editAction($id) {
        $taille = $this->em->getRepository("AppBundle:Taille")->find($id);
        if (!$taille) {
            $this->request->getSession()->getFlashBag()->add('error', 'Taille demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_tailles");
        }
        $form = $this->createForm(\AppBundle\Form\TailleType::class, $taille);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Taille modifiée avec succès !');
                return $this->redirectToRoute("app_admin_tailles");
            }
        }
        return $this->render("AppBundle:admin:tailles_edit.html.twig", array(
                    "taille" => $taille,
                    "formTaille" => $form->createView()
        ));
    }

}
