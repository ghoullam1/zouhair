<?php

namespace AppBundle\Repository;

/**
 * StatutCommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StatutCommandeRepository extends \Doctrine\ORM\EntityRepository {

    public function statutsDispoPourCommande($id_commande) {
        $statuts = $this->_em->createQuery('SELECT s FROM AppBundle:StatutCommande s '
                        . 'WHERE s.id NOT IN '
                        . '(SELECT statut.id FROM AppBundle:CommandeHistorique h JOIN h.statut statut JOIN h.commande c WHERE c.id = :id_commande)'
                )
                ->setParameter("id_commande", $id_commande)
                ->getResult()
        ;
        return $statuts;
    }

}