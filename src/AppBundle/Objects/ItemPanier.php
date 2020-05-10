<?php

namespace AppBundle\Objects;

use AppBundle\Entity\Produit;
use AppBundle\Entity\ProduitVariation;

/**
 * Description of ItemPanier
 *
 * @author abdelhak
 */
class ItemPanier implements \Serializable {

    /**
     *
     * @var string
     */
    protected $reference;

    /**
     *
     * @var string
     */
    protected $titre;

    /**
     * array(
     * id,
     * code,
     * nom
     * )
     * @var array 
     */
    protected $taille;

    /**
     * array(
     * id,
     * code,
     * nom
     * )
     * @var array 
     */
    protected $couleur;

    /**
     *
     * @var string
     */
    protected $image;

    /**
     *
     * @var float
     */
    protected $prix;

    /**
     *
     * @var int
     */
    protected $quantite;

    function __construct(Produit $produit, $quantite, ProduitVariation $variation = null) {
        $this->reference = $produit->getReference();
        $this->titre = $produit->getTitre();
        $this->prix = $produit->getPrix();
        $this->quantite = $quantite;
        $this->image = $produit->getFirstImage()->getFullName();
        if ($variation) {
            if ($variation->getCouleur())
                $this->couleur = array("id" => $variation->getCouleur()->getId(), "code" => $variation->getCouleur()->getCode(), "nom" => $variation->getCouleur()->getNom());
            if ($variation->getTaille())
                $this->taille = array("id" => $variation->getTaille()->getId(), "code" => $variation->getTaille()->getCode(), "nom" => $variation->getTaille()->getNom());
        }
    }

    function getReference() {
        return $this->reference;
    }

    function getTitre() {
        return $this->titre;
    }

    function getTaille() {
        return $this->taille;
    }

    function getCouleur() {
        return $this->couleur;
    }

    function getImage() {
        return $this->image;
    }

    function getPrix() {
        return $this->prix;
    }

    function getQuantite() {
        return $this->quantite;
    }

    function toArray() {
        return array(
            'reference' => $this->reference,
            'titre' => $this->titre,
            'prix' => floatval($this->prix),
            'quantite' => (int) $this->quantite,
            'image' => $this->image,
            'couleur' => $this->couleur,
            'taille' => $this->taille
        );
    }

    function getSousTotal() {
        return $this->prix * $this->quantite;
    }

    function augmentQuantite() {
        $this->quantite ++;
    }

    function demiuerQuantite() {
        if ($this->quantite > 0)
            $this->quantite --;
    }

    function getCodeCouleur() {
        return $this->couleur ? $this->couleur['code'] : null;
    }

    function getCodeTaille() {
        return $this->taille ? $this->taille['code'] : null;
    }

    public function serialize() {
        return serialize(array(
            $this->reference,
            $this->titre,
            $this->prix,
            $this->quantite,
            $this->image,
            $this->couleur,
            $this->taille
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->reference,
                $this->titre,
                $this->prix,
                $this->quantite,
                $this->image,
                $this->couleur,
                $this->taille
                ) = unserialize($serialized);
    }

}
