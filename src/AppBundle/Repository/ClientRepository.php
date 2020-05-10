<?php

namespace AppBundle\Repository;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends \Doctrine\ORM\EntityRepository {

    function liste(array $criteres, $sort, $dir, $start = 0, $max = 10) {

        $qb = $this->_em->createQueryBuilder();
        $qb
                ->from($this->_entityName, "c")
                ->leftJoin('c.ville', "v")
                ->where("c.roles = :roles")
                ->setParameter("roles", 'a:1:{i:0;s:9:"ROLE_USER";}')
        ;

        $total = $qb->select("COUNT(c)")->getQuery()->getSingleScalarResult();

        $operateur = "andWhere";
        if (isset($criteres["id"])) {
            $qb->$operateur("c.id = :id")
                    ->setParameter("id", $criteres["id"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["nom"])) {
            $qb->$operateur("c.nom LIKE :nom")
                    ->setParameter("nom", "%" . $criteres["nom"] . "%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["prenom"])) {
            $qb->$operateur("c.prenom LIKE :prenom")
                    ->setParameter("prenom", "%" . $criteres["prenom"] . "%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["email"])) {
            $qb->$operateur("c.email LIKE :email")
                    ->setParameter("email", "%" . $criteres["email"] . "%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["gsm"])) {
            $qb->$operateur("c.gsm LIKE :gsm")
                    ->setParameter("gsm", "%" . $criteres["gsm"] . "%")
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["dateInscription"])) {
            $date = date_create_from_format('d/m/Y', $criteres["dateInscription"]);
            $date_start = clone $date;
            $date_start->setTime(0, 0, 0);
            $date_end = clone $date;
            $date_end->setTime(23, 59, 59);
            $qb->$operateur("c.dateInscription >= :dateStart")
                    ->setParameter("dateStart", $date_start)
            ;
            $operateur = "andWhere";
            $qb->$operateur("c.dateInscription <= :dateEnd")
                    ->setParameter("dateEnd", $date_end)
            ;
        }
        if (isset($criteres["villeIds"])) {
            $qb
                    ->$operateur("v.id IN (:villeIds)")
                    ->setParameter("villeIds", $criteres["villeIds"])
            ;
            $operateur = "andWhere";
        }


        $totalFiltred = $qb->select("COUNT(c)")->getQuery()->getSingleScalarResult();

        $clients = $qb
                        ->select("DISTINCT c")
                        ->setFirstResult($start)
                        ->setMaxResults($max)
                        ->orderBy($sort, $dir)
                        ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "clients" => $clients);
    }

    function countMe() {
        return $this->_em->createQueryBuilder()
                        ->select("COUNT(c)")
                        ->from($this->_entityName, "c")
                        ->getQuery()->getSingleScalarResult()
        ;
    }

}
