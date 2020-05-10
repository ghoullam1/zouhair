<?php

namespace AppBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository {

    function liste(array $criteres, $sort, $dir, $start = 0, $max = 10) {
        $qb = $this->_em->createQueryBuilder();
        $qb->from($this->_entityName, "p");

        $total = $qb->select("COUNT(p)")->getQuery()->getSingleScalarResult();

        $operateur = "where";
        if (isset($criteres["reference"])) {
            $qb->$operateur("p.reference LIKE :reference")
                    ->setParameter("reference", "%" . $criteres["reference"] . "%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["titre"])) {
            $qb->$operateur("p.titre LIKE :titre")
                    ->setParameter("titre", "%" . $criteres["titre"] . "%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["prix"])) {
            $qb->$operateur("p.prix = :prix")
                    ->setParameter("prix", $criteres["prix"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["stock"])) {
            $qb->$operateur("p.stock = :stock")
                    ->setParameter("stock", $criteres["stock"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["occasion"])) {
            $qb->$operateur("p.occasion = :occasion")
                    ->setParameter("occasion", $criteres["occasion"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["enSolde"])) {
            $qb->$operateur("p.enSolde = :enSolde")
                    ->setParameter("enSolde", $criteres["enSolde"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["isBestProduct"])) {
            $qb->$operateur("p.isBestProduct = :isBestProduct")
                    ->setParameter("isBestProduct", $criteres["isBestProduct"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["categorieSlugs"])) {
            $qb
                    ->join("p.categories", "c")
                    ->$operateur("c.slug IN (:categorieSlugs)")
                    ->setParameter("categorieSlugs", $criteres["categorieSlugs"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["genreIds"])) {
            $qb
                    ->join("p.genres", "g")
                    ->$operateur("g.id IN (:genreIds)")
                    ->setParameter("genreIds", $criteres["genreIds"])
            ;
        }

        $totalFiltred = $qb->select("COUNT(p)")->getQuery()->getSingleScalarResult();

        $produits = $qb
                        ->select("DISTINCT p")
                        ->setFirstResult($start)
                        ->setMaxResults($max)
                        ->orderBy($sort, $dir)
                        ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "produits" => $produits);
    }

    function countMe() {
        return $this->_em->createQueryBuilder()
                        ->select("COUNT(p)")
                        ->from($this->_entityName, "p")
                        ->getQuery()->getSingleScalarResult()
        ;
    }

    function listeFrontOffice(array $criteres, $result = false) {
        $qb = $this->_em->createQueryBuilder()
                ->select("DISTINCT p")
                ->from($this->_entityName, "p")
        ;
        $operateur = "where";
        if (isset($criteres['c'])) {
            $qb
                    ->join("p.categories", "c")
                    ->$operateur("c.slug = :categorieSlug")
                    ->setParameter("categorieSlug", $criteres['c'])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres['g'])) {
            $qb
                    ->join("p.genres", "g")
                    ->$operateur("g.id = :genreId")
                    ->setParameter("genreId", $criteres['g'])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres['min'])) {
            $qb
                    ->$operateur("p.prix >= :min")
                    ->setParameter("min", $criteres['min'])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres['max'])) {
            $qb
                    ->$operateur("p.prix <= :max")
                    ->setParameter("max", $criteres['max'])
            ;
            $operateur = "andWhere";
        }
        return $result ? $qb->getQuery()->getResult() : $qb->getQuery();
    }

    function minAndMaxPrice(array $criteres) {
        $qb = $this->_em->createQueryBuilder()
                ->select("MIN(p.prix) as prixMin,MAX(p.prix) as prixMax")
                ->from($this->_entityName, "p")
        ;
        $operateur = "where";
        if (isset($criteres['c'])) {
            $qb
                    ->join("p.categories", "c")
                    ->$operateur("c.slug = :categorieSlug")
                    ->setParameter("categorieSlug", $criteres['c'])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres['g'])) {
            $qb
                    ->join("p.genres", "g")
                    ->$operateur("g.id = :genreId")
                    ->setParameter("genreId", $criteres['g'])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres['m'])) {
            $qb
                    ->join("p.marque", "m")
                    ->$operateur("m.id = :marqueId")
                    ->setParameter("marqueId", $criteres['m'])
            ;
            $operateur = "andWhere";
        }
        return $qb->getQuery()->getSingleResult();
    }

    function getProduitsLies(\AppBundle\Entity\Produit $produit) {
        return $this->_em->createQueryBuilder()
                        ->select("DISTINCT p")
                        ->from($this->_entityName, "p")
                        ->where("p.reference <> :reference")
                        ->setParameter("reference", $produit->getReference())
                        ->join("p.genres", "g")
                        ->join("p.categories", "c")
                        ->andWhere("g.id IN (:genresIds)")
                        ->andWhere("c.id IN (:categoriesIds)")
                        ->setParameter("genresIds", $produit->getGenresIDs())
                        ->setParameter("categoriesIds", $produit->getCategoriesIDs())
                        ->setMaxResults(10)
                        ->getQuery()->getResult()
        ;
    }

    function bestSeller($limit = 3) {
        return $this->getEntityManager()->createQueryBuilder()
                        ->select("Distinct p,COUNT(c) AS HIDDEN nbr")
                        ->from($this->getEntityName(), "p")
                        ->leftJoin("p.commandes", "pc")
                        ->leftJoin("pc.commande", "c")
                        ->orderBy("nbr", "DESC")
                        ->setMaxResults($limit)
                        ->groupBy("p")
                        ->getQuery()->getResult()
        ;
    }

    function newCollection($limit = 3) {
        return $this->getEntityManager()->createQueryBuilder()
                        ->select("DISTINCT p")
                        ->from($this->getEntityName(), "p")
                        ->where("p.isNew = :new")
                        ->setParameter("new", 1)
                        ->andWhere("p.dateEndNew > CURRENT_DATE()")
                        ->setMaxResults($limit)
                        ->getQuery()->getResult()
        ;
    }

}