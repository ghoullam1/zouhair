<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ClientController
 * @Route("/administration/clients")
 * @author abdelhak
 */
class ClientController extends AdvancedController {

    /**
     * @Route("/", name="app_admin_clients")
     */
    public function indexAction() {

        if ($this->request->isXmlHttpRequest()) {
            $param_sort = array(
                'c.id',
                'c.nom',
                'c.prenom',
                'c.email',
                'c.gsm',
                'c.dateInscription',
                'c.ville'
            );
            $start = $this->request->request->get("start");
            $length = $this->request->request->get("length");
            $sort = $param_sort[$this->request->request->get("order")[0]['column']];
            $dir = $this->request->request->get("order")[0]['dir'];
            $criteres = $this->request->request->has("criteres") ? $this->request->request->get("criteres") : array();
            $requestResult = $this->em->getRepository("AppBundle:Client")->liste($criteres, $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            foreach ($requestResult["clients"] as $client) {
                $resultat['data'][] = array(
                    "id" => $client->getId(),
                    "nom" => $client->getNom(),
                    "prenom" => $client->getPrenom(),
                    "email" => $client->getEmail(),
                    "gsm" => $client->getGsm(),
                    "dateInscription" => $client->getDateInscription()->format("d/m/Y"),
                    "ville" => $client->getVille() ? $client->getVille()->getNom() : '',
                    "commandes" => count($client->getCommandes()),
                    "commentaires" => count($client->getCommentaires()),
                    "action" => '<a class="btn btn-sm btn-primary" href="#"><i class="fa fa-pencil-square-o" title="Modifier" ></i></a>'
                );
            }
            return $this->jsonResponse->setData($resultat);
        }
        $villes = $this->em->getRepository("AppBundle:Ville")->findAll();
        return $this->render("AppBundle:admin:clients.html.twig", array(
                    "villes" => $villes
        ));
    }

}
