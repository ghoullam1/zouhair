<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of CategorieController
 *  @Route("/administration/categories")
 * @author abdelhak
 */
class CategorieController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_categories")
     */
    public function indexAction() {
        $categories = $this->em->getRepository("AppBundle:Categorie")->findBy(
                array(), array('ordre' => 'ASC')
        );
        $categorie = new \AppBundle\Entity\Categorie();
        $form = $this->createForm(\AppBundle\Form\CategorieType::class, $categorie);
        if ($this->request->isMethod("POST")) {
			$form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($categorie);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Catégorie ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_categories");
            }
        }
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest() and $this->request->request->has("ordre")) {
            $ordre = json_decode($this->request->request->get("ordre"));
            if (!is_array($ordre)) {
                return $this->jsonResponse->setContent(json_encode(array(
                            "code" => 0,
                            "message" => "Erreurs lors de l'extraction des données"
                )));
            }
            $index = 1;
            foreach ($ordre as $item) {
                $cat = $this->em->getRepository("AppBundle:Categorie")->find($item->id);
                if ($cat) {
                    $cat->setOrdre($index);
                    $index ++;
                }
            }
            $this->em->flush();
            return $this->jsonResponse->setContent(json_encode(array(
                        "code" => 1,
                        "message" => "Enregistrement effectué avec succès !"
            )));
        }
        return $this->render("AppBundle:admin:categories.html.twig", array(
                    "categories" => $categories,
                    "formCat" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{slug}", name="app_admin_categories_remove")
     */
    public function removeAction($slug) {
        $cat = $this->em->getRepository("AppBundle:Categorie")->findOneBySlug($slug);
        if ($cat) {
            if (count($cat->getProduits()) > 0)
                 $this->request->getSession()->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer une catégorie rattaché à des produits !');
            else {
                $this->em->remove($cat);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Catégorie supprimée avec succès !');
            }
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Catégorie demandée n\'existe pas !');

        return $this->redirectToRoute("app_admin_categories");
    }

    /**
     * @Route("/edit/{slug}", name="app_admin_categories_edit")
     */
    public function editAction($slug) {
        $categorie = $this->em->getRepository("AppBundle:Categorie")->findOneBySlug($slug);
        if (!$categorie) {
            $this->request->getSession()->getFlashBag()->add('error', 'Catégorie demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_categories");
        }
        $form = $this->createForm(\AppBundle\Form\CategorieType::class, $categorie);
        if ($this->request->isMethod("POST")) {
			$form->handleRequest($this->request);
            if ($form->isValid()) {
                if ($this->request->request->has("check_image") and $this->request->request->get("check_image") === "true") {
                    if (!is_null($categorie->getImage()) and ! is_null($categorie->getImage()->getId())) {
                        if (!is_null($categorie->getImage()->getFile())) {
                            $categorie->getImage()->upload();
                        } else {
                            $this->em->remove($categorie->getImage());
                            $categorie->setImage();
                        }
                    }
                }
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Catégorie modifiée avec succès !');
                return $this->redirectToRoute("app_admin_categories");
            }
        }
        return $this->render("AppBundle:admin:categories_edit.html.twig", array(
                    "categorie" => $categorie,
                    "formCat" => $form->createView()
        ));
    }

}
