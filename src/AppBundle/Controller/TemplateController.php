<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of TemplateController
 * @Route("/administration/tamplates")
 * @author abdelhak
 */
class TemplateController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_templates")
     */
    public function indexAction() {
        $templates = $this->em->getRepository("AppBundle:Template")->findAll();
        $template = new \AppBundle\Entity\Template();
        $form = $this->createForm(\AppBundle\Form\TemplateType::class, $template);
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_template")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($template);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Template ajouté avec succès !');
                return $this->redirectToRoute("app_admin_templates");
            }
        }
        return $this->render("AppBundle:admin:templates.html.twig", array(
                    "templates" => $templates,
                    "formTemplate" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_templates_remove")
     */
    public function removeAction($id) {
        $template = $this->em->getRepository("AppBundle:Template")->find($id);
        if ($template) {
            $this->em->remove($template);
            $this->em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'Template supprimé avec succès !');
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Template demandé n\'existe pas !');

        return $this->redirectToRoute("app_admin_templates");
    }
    
     /**
     * @Route("/edit/{id}", name="app_admin_templates_edit")
     */
    public function editAction($id) {
        $template = $this->em->getRepository("AppBundle:Template")->find($id);
        if (!$template) {
            $this->request->getSession()->getFlashBag()->add('error', 'Template demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_templates");
        }
        $form = $this->createForm(\AppBundle\Form\TemplateType::class, $template);
        if ($this->request->isMethod("POST")and $this->request->request->has("appbundle_template")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Template modifié avec succès !');
                return $this->redirectToRoute("app_admin_templates");
            }
        }
        return $this->render("AppBundle:admin:templates_edit.html.twig", array(
                    "template" => $template,
                    "formTemplate" => $form->createView()
        ));
    }

}
