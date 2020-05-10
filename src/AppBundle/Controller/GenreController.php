<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of GenreController
 *  @Route("/administration/genres")
 * @author abdelhak
 */
class GenreController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_genres")
     */
    public function indexAction() {
        $genres = $this->em->getRepository("AppBundle:Genre")->findBy(
                array(), array('ordre' => 'ASC')
        );
        $genre = new \AppBundle\Entity\Genre();
        $form = $this->createForm(\AppBundle\Form\GenreType::class, $genre);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($genre);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Genre ajouté avec succès !');
                return $this->redirectToRoute("app_admin_genres");
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
                $g = $this->em->getRepository("AppBundle:Genre")->find($item->id);
                if ($g) {
                    $g->setOrdre($index);
                    $index ++;
                }
            }
            $this->em->flush();
            return $this->jsonResponse->setContent(json_encode(array(
                        "code" => 1,
                        "message" => "Enregistrement effectué avec succès !"
            )));
        }
        return $this->render("AppBundle:admin:genres.html.twig", array(
                    "genres" => $genres,
                    "formGenre" => $form->createView()
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_admin_genres_remove")
     */
    public function removeAction($id) {
        $genre = $this->em->getRepository("AppBundle:Genre")->find($id);
        if ($genre) {
            if (count($genre->getCategories()) > 0)
                $this->request->getSession()->getFlashBag()->add('notice', 'Vous ne pouvez pas supprimer un genre rattaché à des catégories !');
            else {
                $this->em->remove($genre);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Genre supprimé avec succès !');
            }
        } else
            $this->request->getSession()->getFlashBag()->add('error', 'Genre demandé n\'existe pas !');

        return $this->redirectToRoute("app_admin_genres");
    }

    /**
     * @Route("/edit/{id}", name="app_admin_genres_edit")
     */
    public function editAction($id) {
        $genre = $this->em->getRepository("AppBundle:Genre")->find($id);
        if (!$genre) {
            $this->request->getSession()->getFlashBag()->add('error', 'Genre demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_genres");
        }
        $form = $this->createForm(\AppBundle\Form\GenreType::class, $genre);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Genre modifié avec succès !');
                return $this->redirectToRoute("app_admin_genres");
            }
        }
        return $this->render("AppBundle:admin:genres_edit.html.twig", array(
                    "genre" => $genre,
                    "formGenre" => $form->createView()
        ));
    }

}
