<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Services\Produit\ReferenceGeneration;

/**
 * Description of ProduitController
 * @Route("/administration/produits")
 * @author abdelhak
 */
class ProduitController extends AdvancedController {

    /**
     * @Route("/nouveau", name="app_admin_produits_nouveau")
     */
    public function nouveauAction() {
        $produit = new \AppBundle\Entity\Produit();
        $form = $this->createForm(\AppBundle\Form\ProduitType::class, $produit);
        if ($this->request->isMethod("POST")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $rg = new ReferenceGeneration($this->em);
                $produit->setReference($rg->generateReference());
                $this->em->persist($produit);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Produit ajouté avec succès !');
                return $this->redirectToRoute("app_admin_produits");
            }
        }

        return $this->render("AppBundle:admin:produits_nouveau.html.twig", array(
                    "formProduit" => $form->createView()
        ));
    }

    /**
     * @Route("/", name="app_admin_produits")
     */
    public function indexAction() {
        $categories = $this->em->getRepository("AppBundle:Categorie")->findAll();
        $genres = $this->em->getRepository("AppBundle:Genre")->findAll();
        if ($this->request->isXmlHttpRequest()) {
            $param_sort = array(
                'p.reference',
                'p.titre',
                'p.prix',
                'p.stock'
            );
            $start = $this->request->request->get("start");
            $length = $this->request->request->get("length");
            $sort = $param_sort[$this->request->request->get("order")[0]['column']];
            $dir = $this->request->request->get("order")[0]['dir'];
            $criteres = $this->request->request->has("criteres") ? $this->request->request->get("criteres") : array();
            $requestResult = $this->em->getRepository("AppBundle:Produit")->liste($criteres, $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            foreach ($requestResult["produits"] as $produit) {
                $resultat['data'][] = array(
                    "reference" => $produit->getReference(),
                    "titre" => $produit->getTitre(),
                    "prix" => number_format($produit->getPrix(), 2, ".", " ") . " DH",
                    "stock" => $produit->getStock(),
                    "categories" => $produit->categoriesToString(),
                    "genres" => $produit->genresToString(),
                    "isNew" => $produit->encoreNouveau() ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>',
                    "enSolde" => $produit->getEnSolde() ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>',
                    "occasion" => $produit->getOccasion() ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>',
                    "isBestProduct" => $produit->getIsBestProduct() ? '<span class="label label-success">Oui</span>' : '<span class="label label-danger">Non</span>',
                    "action" => '<a class="btn btn-sm btn-primary" href="' . $this->generateUrl("app_admin_produits_edit", array("id" => $produit->getId())) . '"><i class="fa fa-pencil-square-o" title="Modifier" ></i></a>'
                );
            }
            return $this->jsonResponse->setData($resultat);
        }
        return $this->render("AppBundle:admin:produits.html.twig", array(
                    "categories" => $categories,
                    "genres" => $genres
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_admin_produits_edit")
     */
    public function editAction($id) {
        $produit = $this->em->getRepository("AppBundle:Produit")->find($id);
        if (!$produit) {
            $this->request->getSession()->getFlashBag()->add('error', 'Produit demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_produits");
        }
        $form = $this->createForm(\AppBundle\Form\ProduitEditType::class, $produit);
        $image = new \AppBundle\Entity\Image();
        $formImage = $this->createForm(\AppBundle\Form\ImageType::class, $image);
        $produitVariation = new \AppBundle\Entity\ProduitVariation();
        $formVariation = $this->createForm(\AppBundle\Form\ProduitVariationType::class, $produitVariation);

        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_produit")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Produit modifié avec succès !');
                return $this->redirectToRoute("app_admin_produits_edit", array("id" => $produit->getId()));
            }
        }
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_image")) {
            $formImage->handleRequest($this->request);
            if ($formImage->isValid()) {
                $image->setProduit($produit);
                $this->em->persist($image);
                $this->em->flush();
                $produit->addImage($image);
                $this->request->getSession()->getFlashBag()->add('success', 'Image ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_produits_edit", array("id" => $produit->getId()));
            }
        }
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_produitvariation")) {
            $formVariation->handleRequest($this->request);
            if ($formVariation->isValid()) {
                $produitVariation->setProduit($produit);
                $this->em->persist($produitVariation);
                $this->em->flush();
                $produit->addVariation($produitVariation);
                $this->request->getSession()->getFlashBag()->add('success', 'Variation ajoutée avec succès !');
                return $this->redirectToRoute("app_admin_produits_edit", array("id" => $produit->getId()));
            }
        }

        return $this->render("AppBundle:admin:produits_edit.html.twig", array(
                    "formProduit" => $form->createView(),
                    "formImage" => $formImage->createView(),
                    "formVariation" => $formVariation->createView(),
                    "produit" => $produit
        ));
    }

    /**
     * @Route("/image/remove/{id_produit}/{id_image}", name="app_admin_produits_image_remove")
     */
    public function imageRemoveAction($id_produit, $id_image) {
        $produit = $this->em->getRepository("AppBundle:Produit")->find($id_produit);
        $image = $this->em->getRepository("AppBundle:Image")->find($id_image);
        if (!$produit) {
            $this->request->getSession()->getFlashBag()->add('error', 'Produit demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_produits");
        } elseif (!$image)
            $this->request->getSession()->getFlashBag()->add('error', 'Image demandée n\'existe pas !');
        elseif (is_null($image->getProduit()) or ( !is_null($image->getProduit()) and $image->getProduit()->getId() !== $produit->getId()))
            $this->request->getSession()->getFlashBag()->add('error', 'Cette image  n\'appartient pas à ce produit !');
        else {
            $this->em->remove($image);
            $this->em->flush();
            $this->request->getSession()->getFlashBag()->add('success', 'Image supprimée avec succès !');
        }
        return $this->redirectToRoute("app_admin_produits_edit", array("id" => $produit->getId()));
    }

}
