<?php

namespace AppBundle\Objects;

use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Objects\ItemPanier;
use Doctrine\ORM\EntityManager;

/**
 * Description of Panier
 *
 * @author abdelhak
 */
class Panier implements \Serializable {

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * array(
     * id,
     * libelle,
     * remise,
     * pourcentage,
     * freeShipping
     * )
     * @var array
     */
    protected $coupon;

    /**
     * array(
     * id,
     * libelle,
     * frais
     * )
     * @var array
     */
    protected $modeLivraison;

    function __construct(EntityManager $em) {
        $this->items = new ArrayCollection();
        $this->em = $em;
    }

    /**
     * @param ItemPanier $item
     */
    function addItem(ItemPanier $item) {
        if ($this->hasItem($item))
            return false;
        else {
            $this->items->add($item);
            return true;
        }
    }

    /**
     * @param ItemPanier $item
     */
    function removeItem($item) {
        foreach ($this->items as $object) {
            if ($item->getReference() === $object->getReference() and $item->getCouleur() === $object->getCouleur() and $item->getTaille() === $object->getTaille()) {
                $this->items->removeElement($object);
                break;
            }
        }
    }

    /**
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     */
    function _removeItem($reference, $couleur, $taille) {
        foreach ($this->items as $object) {
            if ($reference === $object->getReference() and $couleur === $object->getCodeCouleur() and $taille === $object->getCodeTaille()) {
                $this->items->removeElement($object);
                break;
            }
        }
    }

    /**
     * @param ItemPanier $item
     */
    function augmentQuantite($item) {
        foreach ($this->items as $object) {
            if ($item->getReference() === $object->getReference() and $item->getCouleur() === $object->getCouleur() and $item->getTaille() === $object->getTaille()) {
                $object->augmentQuantite();
                break;
            }
        }
    }

    /**
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     */
    function _augmentQuantite($reference, $couleur, $taille) {
        foreach ($this->items as $object) {
            if ($reference === $object->getReference() and $couleur === $object->getCodeCouleur() and $taille === $object->getCodeTaille()) {
                $object->augmentQuantite();
                break;
            }
        }
    }

    /**
     * @param ItemPanier $item
     */
    function demiuerQuantite($item) {
        foreach ($this->items as $object) {
            if ($item->getReference() === $object->getReference() and $item->getCouleur() === $object->getCouleur() and $item->getTaille() === $object->getTaille()) {
                if ($object->getQuantite() > 0)
                    $object->demiuerQuantite();
                else
                    $this->removeItem($item);
                break;
            }
        }
    }

    /**
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     */
    function _demiuerQuantite($reference, $couleur, $taille) {
        foreach ($this->items as $object) {
            if ($reference === $object->getReference() and $couleur === $object->getCodeCouleur() and $taille === $object->getCodeTaille()) {
                if ($object->getQuantite() > 0)
                    $object->demiuerQuantite();
                else
                    $this->removeItem($object);
                break;
            }
        }
    }

    /**
     * @param ItemPanier $item
     * @return boolean
     */
    function hasItem(ItemPanier $item) {
        $exist = false;
        foreach ($this->items as $objet) {
            if ($item->getReference() === $objet->getReference() and $item->getTaille() === $objet->getTaille() and $item->getCouleur() === $objet->getCouleur()) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    /**
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     * 
     * @return boolean
     */
    function _hasItem($reference, $couleur, $taille) {
        $exist = false;
        foreach ($this->items as $object) {
            if ($reference === $object->getReference() and $couleur === $object->getCodeCouleur() and $taille === $object->getCodeTaille()) {
                $exist = true;
                break;
            }
        }
        return $exist;
    }

    function toArray() {
        $panier = array("items" => array(), "coupon" => $this->coupon, "modeLivraison" => $this->modeLivraison);
        foreach ($this->items as $item) {
            $panier["items"][] = $item->toArray();
        }
        return $panier;
    }

    function getItems() {
        return $this->items;
    }

    function getCoupon() {
        return $this->coupon;
    }

    function setCoupon($coupon) {
        $this->coupon = $coupon;
    }

    function getModeLivraison() {
        return $this->modeLivraison;
    }

    function setModeLivraison($modeLivraison) {
        $this->modeLivraison = $modeLivraison;
    }

    function getSousTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->getSousTotal();
        }
        return $total;
    }

    function getRemise() {
        $remise = 0;
        if (!is_null($this->coupon)) {
            if (is_null($this->coupon['freeShipping']))
                $remise = 0;
            else {
                if ($this->coupon['pourcentage'])
                    $remise = $this->getSousTotal() / 100 * $this->coupon['remise'];
                else
                    $remise = $this->coupon['remise'];
            }
        }
        return $remise;
    }

    function getTotal() {
        $total = $this->getSousTotal();
        $remise = $this->getRemise();
        $total -= $remise;
        if (!is_null($this->modeLivraison) and ! is_null($this->coupon) and ! $this->coupon['freeShipping']) {
            $total += $this->modeLivraison['frais'];
        } elseif (!is_null($this->modeLivraison) and is_null($this->coupon)) {
            $total += $this->modeLivraison['frais'];
        }
        return $total;
    }

    public function serialize() {
        return serialize(array(
            $this->items,
            $this->coupon,
            $this->modeLivraison
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->items,
                $this->coupon,
                $this->modeLivraison
                ) = unserialize($serialized);
    }

    public function clear() {
        $this->items->clear();
        $this->coupon = $this->modeLivraison = null;
    }

    /**
     * 
     * @param string $reference
     * @return int
     */
    public function getQuantiteCommandeForReference($reference) {
        $quantite = 0;
        foreach ($this->items as $item) {
            if ($item->getReference() === $reference)
                $quantite += $item->getQuantite();
        }
        return $quantite;
    }

    /**
     * 
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     * @return int
     */
    public function getQuantiteCommandeForVariation($reference, $couleur, $taille) {
        $quantite = 0;
        foreach ($this->items as $item) {
            if ($item->getReference() === $reference and $item->getCodeCouleur() === $couleur and $item->getCodeTaille() === $taille)
                $quantite += $item->getQuantite();
        }
        return $quantite;
    }

    /**
     * 
     * @param string $reference
     * @param string $couleur
     * @param string $taille
     * @return ItemPanier
     */
    function getItem($reference, $couleur, $taille) {
        foreach ($this->items as $object) {
            if ($reference === $object->getReference() and $couleur === $object->getCodeCouleur() and $taille === $object->getCodeTaille()) {
                return $object;
            }
        }
        return null;
    }

    function couponToString() {
        $string = "";
        if ($this->coupon) {
            $string .= $this->coupon['libelle'];
            if ($this->coupon['freeShipping'])
                $string .= " - Livraison gratuite";
            else {
                $string .= " - " . $this->coupon['remise'];
                $string .= $this->coupon['pourcentage'] ? " %" : " DH";
            }
        }
        return $string === "" ? null : $string;
    }

}
