<?php

namespace AppBundle\Services\Produit;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Commande;

/**
 * Description of StockManager
 *
 * @author abdelhak
 */
class StockManager {

    /**
     *
     * @var EntityManager
     */
    private $em;

    function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Diminuer la quantite du stock des produits de la commande
     * @param Commande $commande
     */
    function updateStock(Commande $commande) {
        foreach ($commande->getProduits() as $pc) {
            $produit = $pc->getProduit();
            $produit->setStock($produit->getStock() - $pc->getQuantite());
            if ($pc->getVariation()) {
                $pc->getVariation()->setStock($pc->getVariation()->getStock() - $pc->getQuantite());
            }
        }
        $this->em->flush();
    }

}
