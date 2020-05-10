<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of CouleurController
 * @Route("/administration/couleurs")
 * @author abdelhak
 */
class CouleurController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_couleurs")
     */
    public function indexAction() {
        $couleurs = $this->em->getRepository("AppBundle:Couleur")->findAll();
        $couleur = new \AppBundle\Entity\Couleur();
        $form = $this->createForm(\AppBundle\Form\CouleurType::class, $couleur);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($couleur);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Couleur ajouté avec succès !');
                return $this->redirectToRoute("app_admin_couleurs");
            }
        }
        return $this->render("AppBundle:admin:couleurs.html.twig", array(
                    "couleurs" => $couleurs,
                    "formCouleur" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_couleurs_remove")
     */
    public function removeAction($id) {
        $couleur = $this->em->getRepository("AppBundle:Couleur")->find($id);
        if ($couleur) {
            $this->em->remove($couleur);
            $this->em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'Couleur supprimé avec succès !');
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Couleur demandé n\'existe pas !');

        return $this->redirectToRoute("app_admin_couleurs");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_couleurs_edit")
     */
    public function editAction($id) {
        $couleur = $this->em->getRepository("AppBundle:Couleur")->find($id);
        if (!$couleur) {
            $this->request->getSession()->getFlashBag()->add('error', 'Couleur demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_couleurs");
        }
        $form = $this->createForm(\AppBundle\Form\CouleurType::class, $couleur);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Couleur modifié avec succès !');
                return $this->redirectToRoute("app_admin_couleurs");
            }
        }
        return $this->render("AppBundle:admin:couleurs_edit.html.twig", array(
                    "couleur" => $couleur,
                    "formCouleur" => $form->createView()
        ));
    }

}
