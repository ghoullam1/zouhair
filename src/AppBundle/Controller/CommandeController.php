<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of CommandeController
 * @Route("/administration/commandes")
 * @author abdelhak
 */
class CommandeController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_commandes")
     */
    public function indexAction() {
        $modesLivraison = $this->em->getRepository("AppBundle:ModeLivraison")->findAll();
        $statutsCommande = $this->em->getRepository("AppBundle:StatutCommande")->findAll();
        if ($this->request->isXmlHttpRequest()) {
            $param_sort = array(
                'c.reference',
                'clt.id',
                'c.total',
                'c.fraisLivraison',
                'c.dateCommande'
            );
            $start = $this->request->request->get("start");
            $length = $this->request->request->get("length");
            $sort = $param_sort[$this->request->request->get("order")[0]['column']];
            $dir = $this->request->request->get("order")[0]['dir'];
            $criteres = $this->request->request->has("criteres") ? $this->request->request->get("criteres") : array();
            $requestResult = $this->em->getRepository("AppBundle:Commande")->liste($criteres, $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            foreach ($requestResult["commandes"] as $commande) {
                $resultat['data'][] = array(
                    "reference" => $commande->getReference(),
                    "client" => $commande->getClient()->getNom() . "  " . $commande->getClient()->getPrenom(),
                    "total" => number_format($commande->getTotal(), 2, ".", " ") . " DH",
                    "fraisLivraison" => number_format($commande->getFraisLivraison(), 2, ".", " ") . " DH",
                    "dateCommande" => $commande->getDateCommande()->format("d/m/Y"),
                    "modesLivrais" => $commande->getModeLivraison()->getLibelle(),
                    "remise" => $commande->getRemise() ? '- ' . number_format($commande->getRemise(), 2, ".", " ") . " DH" : '',
                    "statut" => $commande->getLastStatut() ? $commande->getLastStatut()->getLibelle() : '',
                    "action" => '<a class="btn btn-sm btn-primary" href="' . $this->generateUrl("app_admin_commandes_details", array("reference" => $commande->getReference())) . '"><i class="fa fa-pencil-square-o" title="Modifier" ></i></a>'
                );
            }
            return $this->jsonResponse->setData($resultat);
        }
        return $this->render("AppBundle:admin:commandes.html.twig", array(
                    "modesLivraison" => $modesLivraison,
                    "statutsCommande" => $statutsCommande
        ));
    }

    /**
     * @Route("/details/{reference}", name="app_admin_commandes_details")
     */
    public function detailsAction($reference) {
        $commande = $this->em->getRepository("AppBundle:Commande")->findOneByReference($reference);
        if (!$commande) {
            $this->request->getSession()->getFlashBag()->add('error', 'Commande demandée n\'existe pas !');
            return $this->redirectToRoute("app_admin_commandes");
        }

        if ($this->request->isMethod("POST") and $this->request->request->has("statut")) {
            $statut = $this->em->getRepository("AppBundle:StatutCommande")->find($this->request->request->get("statut"));
            if (!$statut) {
                $this->request->getSession()->getFlashBag()->add('error', 'Statut demandé n\'existe pas !');
                return $this->redirectToRoute("app_admin_commandes_details", array("reference" => $commande->getReference()));
            }
            $statutManager = $this->get('app.commande_historique');
            $statutManager->addStatut($commande, $statut);
            $this->request->getSession()->getFlashBag()->add('success', 'Statuts ajouté avec succès !');
            return $this->redirectToRoute("app_admin_commandes_details", array("reference" => $commande->getReference()));
        }

        $statutsCommandeDisponible = $this->em->getRepository("AppBundle:StatutCommande")->statutsDispoPourCommande($commande->getId());

        return $this->render("AppBundle:admin:commandes_details.html.twig", array(
                    "commande" => $commande,
                    "statutsCommande" => $statutsCommandeDisponible
        ));
    }

}
