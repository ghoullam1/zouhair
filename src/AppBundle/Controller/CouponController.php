<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of CouponController
 * @Route("/administration/coupons")
 * @author abdelhak
 */
class CouponController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_coupons")
     */
    public function indexAction() {

        $coupon = new \AppBundle\Entity\Coupon();
        $form = $this->createForm(\AppBundle\Form\CouponType::class, $coupon);
        if ($this->request->isXmlHttpRequest()) {
            $param_sort = array(
                'c.code',
                'c.libelle',
                'c.valeur',
                'c.actif',
                'c.freeShipping',
                'c.pourcentage',
                'c.dateDebut',
                'c.dateFin',
                'c.montantMin',
                'c.montantMax'
            );
            $start = $this->request->request->get("start");
            $length = $this->request->request->get("length");
            $sort = $param_sort[$this->request->request->get("order")[0]['column']];
            $dir = $this->request->request->get("order")[0]['dir'];
            $criteres = $this->request->request->has("criteres") ? $this->request->request->get("criteres") : array();
            $requestResult = $this->em->getRepository("AppBundle:Coupon")->liste($criteres, $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            foreach ($requestResult["coupons"] as $c) {
                $resultat['data'][] = array(
                    "code" => $c->getCode(),
                    "libelle" => $c->getLibelle(),
                    "valeur" => $c->getValeur() ? number_format($c->getValeur(), 2, ".", " ") . ($c->getPourcentage() ? "%" : " DH") : "",
                    "actif" => $c->getActif() ? "<span class='label label-success'>Oui</span>" : "<span class='label label-danger'>Non</span>",
                    "freeShipping" => $c->getFreeShipping() ? "<span class='label label-success'>Oui</span>" : "<span class='label label-danger'>Non</span>",
                    "pourcentage" => $c->getPourcentage() ? "<span class='label label-success'>Pourcentage</span>" : "<span class='label label-warning'>Montant</span>",
                    "dateDebut" => $c->getDateDebut() ? $c->getDateDebut()->format("d/m/Y H:i") : "",
                    "dateFin" => $c->getDateFin() ? $c->getDateFin()->format("d/m/Y H:i") : "",
                    "montantMin" => $c->getMontantMin() ? number_format($c->getMontantMin(), 2, ".", " ") . " DH" : "",
                    "montantMax" => $c->getMontantMax() ? number_format($c->getMontantMax(), 2, ".", " ") . " DH" : "",
                    "action" => '<a class="btn btn-sm btn-primary" href="' . $this->generateUrl("app_admin_coupons_edit", array("id" => $c->getId())) . '"><i class="fa fa-pencil-square-o" title="Modifier" ></i></a>'
                );
            }
            return $this->jsonResponse->setData($resultat);
        }
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_coupon")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->persist($coupon);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Bon de réduction ajouté avec succès !');
                return $this->redirectToRoute("app_admin_coupons");
            }
            var_dump($form->get('dateDebut')->getErrors());
            exit();
        }
        return $this->render("AppBundle:admin:coupons.html.twig", array(
                    "formCoupon" => $form->createView()
        ));
    }

    /**
     * @Route("/{id}", name="app_admin_coupons_edit")
     */
    public function editAction($id) {

        $coupon = $this->em->getRepository("AppBundle:Coupon")->find($id);
        if (!$coupon) {
            $this->request->getSession()->getFlashBag()->add('error', 'Bon de réduction demandé n\'existe pas !');
            return $this->redirectToRoute("app_admin_coupons");
        }
        $form = $this->createForm(\AppBundle\Form\CouponType::class, $coupon);
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_coupon")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Bon de réduction modifié avec succès !');
                return $this->redirectToRoute("app_admin_coupons");
            }
        }
        return $this->render("AppBundle:admin:coupons_edit.html.twig", array(
                    "coupon" => $coupon,
                    "formCoupon" => $form->createView()
        ));
    }

}
