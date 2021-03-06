<?php

namespace AppBundle\Repository;

/**
 * TailleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TailleRepository extends \Doctrine\ORM\EntityRepository {

    function tailleDispoPourProduit($reference, $id_couleur = null) {
        $qb = $this->_em->createQueryBuilder()
                ->select("DISTINCT t.id")
                ->from("AppBundle:ProduitVariation", "v")
                ->join("v.taille", "t")
                ->join("v.produit", "p")
                ->where("p.reference = '$reference'")
        ;
        if ($id_couleur) {
            $qb
                    ->join("v.couleur", "c")
                    ->where("c.id = $id_couleur")
            ;
        }
        return $this->_em->createQueryBuilder()
                        ->select("taille")
                        ->from($this->_entityName, "taille")
                        ->where("taille.id IN (" . $qb->getDQL() . ")")
                        ->getQuery()->getResult()
        ;
    }

}
