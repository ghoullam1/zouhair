<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of SlideController
 * @Route("/administration/slides")
 * @author abdelhak
 */
class SlideController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_slides")
     */
    public function indexAction() {
        $slides = $this->em->getRepository("AppBundle:Slide")->findBy(
                array(), array('ordre' => 'ASC')
        );
        $newSlide = new \AppBundle\Entity\Slide();
        $form = $this->createForm(\AppBundle\Form\SlideType::class, $newSlide);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($newSlide);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Slide ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_slides");
            }
        }
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest() and $this->request->request->has("ordre")) {
            $ordre = json_decode($this->request->request->get("ordre"), true);

            if (!is_array($ordre)) {
                return $this->jsonResponse->setContent(json_encode(array(
                            "code" => 0,
                            "message" => "Erreurs lors de l'extraction des données"
                )));
            }
            $index = 1;
            foreach ($ordre as $item) {
                $slide = $this->em->getRepository("AppBundle:Slide")->find($item);
                if ($slide) {
                    $slide->setOrdre($index);
                    $index ++;
                }
            }
            $this->em->flush();
            return $this->jsonResponse->setContent(json_encode(array(
                        "code" => 1,
                        "message" => "Enregistrement effectué avec succès !"
            )));
        }
        return $this->render("AppBundle:admin:slides.html.twig", array(
                    "slides" => $slides,
                    "formSlide" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_slides_remove")
     */
    public function removeAction($id) {
        $slide = $this->em->getRepository("AppBundle:Slide")->find($id);
        if ($slide) {
            $this->em->remove($slide);
            $this->em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'Slide supprimée avec succès !');
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Slide demandée n\'existe pas !');

        return $this->redirectToRoute("app_admin_slides");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_slides_edit")
     */
    public function editAction($id) {
        $slide = $this->em->getRepository("AppBundle:Slide")->find($id);
        if (!$slide) {
            $this->request->getSession()->getFlashBag()->add('error', 'Slide demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_slides");
        }
        $form = $this->createForm(\AppBundle\Form\SlideType::class, $slide);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                if ($this->request->request->has("check_image") and $this->request->request->get("check_image") === "true") {
                    $slide->getImage()->upload();
                }
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Slide modifiée avec succès !');
                return $this->redirectToRoute("app_admin_slides");
            }
        }
        return $this->render("AppBundle:admin:slides_edit.html.twig", array(
                    "slide" => $slide,
                    "formSlide" => $form->createView()
        ));
    }

}
