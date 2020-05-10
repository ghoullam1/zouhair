<?php

namespace AppBundle\Services\Panier;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use AppBundle\Objects\ItemPanier;
use AppBundle\Objects\Panier;
use AppBundle\Entity\Produit;

/**
 * Description of PanierManager
 *
 * @author abdelhak
 */
class PanierManager {

    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     *
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     *
     * @var string
     */
    private $msg;

    /**
     *
     * @var boolean
     */
    private $statut;

    /**
     *
     * @var int
     */
    private $code;

    /**
     * 
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     */
    function __construct(EntityManager $em, TokenStorage $tokenStorage) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * 
     * @param Panier $panier
     * @param Produit $produit
     * @param int $quantite
     * @param int $couleur
     * @param int $taille
     */
    function addItemToPanier(Panier &$panier, Produit $produit, $quantite, $couleur, $taille) {
        if ($produit->getStock() < $quantite) {
            $this->setInfo("La quantité demandée n'est pas disponible!", false, -1);
            return false;
        }
        if (is_null($taille) and is_null($couleur)) {
            $panierItem = new ItemPanier($produit, $quantite);
        } else {
            $variation = $this->em->getRepository("AppBundle:ProduitVariation")->findOneBy(array(
                "produit" => $produit->getId(),
                "taille" => $taille,
                "couleur" => $couleur
            ));

            if (!$variation) {
                $this->setInfo("Le produit '" . $produit->getTitre() . "' n'est pas disponible!", false, 0);
                return false;
            }
            if ($variation->getStock() < $quantite) {
                $this->setInfo("La quantité demandée n'est pas disponible!", false, -1);
                return false;
            }
            $panierItem = new ItemPanier($produit, $quantite, $variation);
        }
        if ($panier->hasItem($panierItem)) {
            $this->setInfo("Vous avez dèja ce produit sur votre panier!", false, -1);
            return false;
        }
        $panier->addItem($panierItem);
        $this->setInfo("Le produit '" . $produit->getTitre() . "' est ajouté au panier", true, 1);
        return true;
    }

    /**
     * 
     * @param Panier $panier
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     * @return boolean
     */
    function removeItemFromPanier(Panier &$panier, $reference, $couleur, $taille) {
        if (!$panier->_hasItem($reference, $couleur, $taille)) {
            $this->setInfo("Votre panier ne contient pas ce produit!", false, 0);
            return false;
        }
        $panier->_removeItem($reference, $couleur, $taille);
        $this->setInfo("Le produit '" . $reference . "' est supprimé du panier", true, 1);
        return true;
    }

    /**
     * 
     * @param Panier $panier
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     * @return boolean
     */
    function augmenteQuantite(Panier &$panier, $reference, $couleur, $taille) {
        if (!$panier->_hasItem($reference, $couleur, $taille)) {
            $this->setInfo("Votre panier ne contient pas ce produit!", false, 0);
            return false;
        }
        $produit = $this->em->getRepository("AppBundle:Produit")->findOneByReference($reference);
        if (!$produit) {
            $this->setInfo("Produit Introuvable!", false, 0);
            return false;
        }
        $quantiteCommande = $panier->getQuantiteCommandeForReference($reference);
        if ($produit->getStock() <= $quantiteCommande) {
            $this->setInfo("La quantité demandée n'est pas disponible!", false, -1);
            return false;
        }
        if (!is_null($couleur) OR ! is_null($taille)) {
            $item = $panier->getItem($reference, $couleur, $taille);
            $variation = $this->em->getRepository("AppBundle:ProduitVariation")->findOneBy(array(
                "produit" => $produit->getId(),
                "taille" => $item->getTaille()['id'],
                "couleur" => $item->getCouleur()['id']
            ));

            if (!$variation) {
                $this->setInfo("Le produit '" . $produit->getTitre() . "' n'est pas disponible!", false, 0);
                return false;
            }
            $quantiteCommande = $panier->getQuantiteCommandeForVariation($reference, $couleur, $taille);
            if ($variation->getStock() <= $quantiteCommande) {
                $this->setInfo("La quantité demandée n'est pas disponible!", false, -1);
                return false;
            }
        }
        $panier->_augmentQuantite($reference, $couleur, $taille);
        $this->setInfo("Quantité augmentée avec succès", true, 1);
        return false;
    }

    /**
     * 
     * @param Panier $panier
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     * @return boolean
     */
    function deminuerQuantite(Panier &$panier, $reference, $couleur, $taille) {
        if (!$panier->_hasItem($reference, $couleur, $taille)) {
            $this->setInfo("Votre panier ne contient pas ce produit!", false, 0);
            return false;
        }
        $produit = $this->em->getRepository("AppBundle:Produit")->findOneByReference($reference);
        if (!$produit) {
            $this->setInfo("Produit Introuvable!", false, 0);
            return false;
        }
        $item = $panier->getItem($reference, $couleur, $taille);
        if ($item->getQuantite() <= 1) {
            $this->setInfo("La quantité commandée ne doit pas être inférieure a 1", false, -1);
            return false;
        }
        $panier->demiuerQuantite($item);
        $this->setInfo("Quantité diminuée avec succès", true, 1);
        return false;
    }

    function addCoupon(Panier &$panier, $code) {
        $coupon = $this->em->getRepository("AppBundle:Coupon")->findOneByCode($code);
        if (!$coupon) {
            $this->setInfo("Le code saisie n'est pas valide!", false, 0);
            return false;
        }
        if (count($panier->getItems()) === 0) {
            $this->setInfo("Vous devez commander des produits avant d'ajouter un coupon", false, -1);
            return false;
        }
        if (!is_null($panier->getCoupon())) {
            $this->setInfo("Vous avez dèjà ajouter un coupon", false, -1);
            return false;
        }
        $client = $this->tokenStorage->getToken()->getUser();
        if (!$coupon->isValid($panier->getSousTotal(), $client)) {
            $this->setInfo("Vous n'avez pas le droit d'utiliser ce bon de réduction!", false, -1);
            return false;
        }
        $panier->setCoupon(array(
            "id" => $coupon->getId(),
            "libelle" => $coupon->getLibelle(),
            "remise" => $coupon->getValeur(),
            "pourcentage" => $coupon->getPourcentage(),
            "freeShipping" => $coupon->getFreeShipping()
        ));
        $this->setInfo("Bon de réduction ajouté avec succès", true, 1);
        return true;
    }

    function removeCoupon(Panier &$panier) {
        $panier->setCoupon(null);
        $this->setInfo("Bon de réduction supprimé avec succès", true, 1);
        return true;
    }

    function removeModeLivraison(Panier &$panier) {
        $panier->setModeLivraison(null);
        $this->setInfo("Le mode de livraison supprimé avec succès", true, 1);
        return true;
    }
/**
 * 
 * @param Panier $panier
 * @param int $id_mode
 * @return boolean
 */
    function addModeLivraison(Panier &$panier, $id_mode) {
        $mode = $this->em->getRepository("AppBundle:ModeLivraison")->find($id_mode);
        if (!$mode) {
            $this->setInfo("Le mode de livraison sélectionné n'existe pas !", false, 0);
            return false;
        }
        $client = $this->tokenStorage->getToken()->getUser();
        if ($mode->getVille()->getId() !== $client->getVille()->getId()) {
            $this->setInfo("Le mode de livraison sélectionné n'appartient pas à votre ville '" . $client->getVille()->getNom() . "' !", false, 0);
            return false;
        }
        $panier->setModeLivraison(array(
            "id" => $mode->getId(),
            "libelle" => $mode->getLibelle(),
            "frais" => $mode->getFraisLivraison()
        ));
        $this->setInfo("Le mode de livraison ajouté avec succès", true, 1);
        return true;
    }

    function getMsg() {
        return $this->msg;
    }

    function getStatut() {
        return $this->statut;
    }

    function setMsg($msg) {
        $this->msg = $msg;
    }

    function setStatut($statut) {
        $this->statut = $statut;
    }

    function getCode() {
        return $this->code;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setInfo($msg, $statut, $code) {
        $this->setMsg($msg);
        $this->setStatut($statut);
        $this->setCode($code);
    }

}
