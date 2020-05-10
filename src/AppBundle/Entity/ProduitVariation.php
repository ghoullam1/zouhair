<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitVariation
 *
 * @ORM\Table(name="produit_variation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitVariationRepository")
 */
class ProduitVariation {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit",inversedBy="variations")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="Couleur")
     * @ORM\JoinColumn(name="couleur_id", referencedColumnName="id", nullable=true)
     */
    private $couleur;

    /**
     * @ORM\ManyToOne(targetEntity="Taille")
     * @ORM\JoinColumn(name="taille_id", referencedColumnName="id", nullable=true)
     */
    private $taille;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitCommande", mappedBy="variation", cascade={"persist","remove"})
     */
    private $commandes;

    function __construct() {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return produitVariation
     */
    public function setStock($stock) {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock() {
        return $this->stock;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return produitVariation
     */
    public function setProduit(\AppBundle\Entity\Produit $produit = null) {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\Produit
     */
    public function getProduit() {
        return $this->produit;
    }

    /**
     * Set couleur
     *
     * @param \AppBundle\Entity\Couleur $couleur
     *
     * @return produitVariation
     */
    public function setCouleur(\AppBundle\Entity\Couleur $couleur = null) {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return \AppBundle\Entity\Couleur
     */
    public function getCouleur() {
        return $this->couleur;
    }

    /**
     * Set taille
     *
     * @param \AppBundle\Entity\Taille $taille
     *
     * @return produitVariation
     */
    public function setTaille(\AppBundle\Entity\Taille $taille = null) {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get taille
     *
     * @return \AppBundle\Entity\Taille
     */
    public function getTaille() {
        return $this->taille;
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\ProduitCommande $commande
     *
     * @return ProduitVariation
     */
    public function addCommande(\AppBundle\Entity\ProduitCommande $commande) {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \AppBundle\Entity\ProduitCommande $commande
     */
    public function removeCommande(\AppBundle\Entity\ProduitCommande $commande) {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes() {
        return $this->commandes;
    }

}
