<?php

namespace AppBundle\Repository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    function findForGenre($id_genre){
        return $this->_em->createQueryBuilder()
                ->select("DISTINCT c")
                ->from($this->_entityName, "c")
                ->join("c.genres", "g")
                ->where("g.id = :id_genre")
                ->setParameter("id_genre", $id_genre)
                ->getQuery()->getResult()
                ;
    }
}
