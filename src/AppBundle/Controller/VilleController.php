<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of VilleController
 * @Route("/administration/villes")
 * @author abdelhak
 */
class VilleController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_villes")
     */
    public function indexAction() {
        $villes = $this->em->getRepository("AppBundle:Ville")->findAll();
        $ville = new \AppBundle\Entity\Ville();
        $form = $this->createForm(\AppBundle\Form\VilleType::class, $ville);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($ville);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Ville ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_villes");
            }
        }
        return $this->render("AppBundle:admin:villes.html.twig", array(
                    "villes" => $villes,
                    "formVille" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_villes_remove")
     */
    public function removeAction($id) {
        $ville = $this->em->getRepository("AppBundle:Ville")->find($id);
        if ($ville) {
            if (count($ville->getClients()) > 0)
                $this->request->getSession()->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer une ville rattaché à des clients !');
            else {
                $this->em->remove($ville);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Ville supprimée avec succès !');
            }
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Ville demandée n\'existe pas !');

        return $this->redirectToRoute("app_admin_villes");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_villes_edit")
     */
    public function editAction($id) {
        $ville = $this->em->getRepository("AppBundle:Ville")->find($id);
        if (!$ville) {
            $this->request->getSession()->getFlashBag()->add('error', 'Ville demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_villes");
        }
        $form = $this->createForm(\AppBundle\Form\VilleType::class, $ville);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Ville modifiée avec succès !');
                return $this->redirectToRoute("app_admin_villes");
            }
        }
        return $this->render("AppBundle:admin:villes_edit.html.twig", array(
                    "ville" => $ville,
                    "formVille" => $form->createView()
        ));
    }

}
