<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitCommande
 *
 * @ORM\Table(name="produit_commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitCommandeRepository")
 */
class ProduitCommande {

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
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var Commande
     * @ORM\ManyToOne(targetEntity="Commande",inversedBy="produits")
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     */
    private $commande;

    /**
     * @var string
     *
     * @ORM\Column(name="prixUnitaire", type="decimal", precision=10, scale=2)
     */
    private $prixUnitaire;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit",inversedBy="commandes")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * @var ProduitVariation
     * @ORM\ManyToOne(targetEntity="ProduitVariation",inversedBy="commandes")
     * @ORM\JoinColumn(name="variation_id", referencedColumnName="id", nullable=true)
     */
    private $variation;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return ProduitCommande
     */
    public function setQuantite($quantite) {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite() {
        return $this->quantite;
    }

    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return ProduitCommande
     */
    public function setCommande(\AppBundle\Entity\Commande $commande = null) {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande() {
        return $this->commande;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return ProduitCommande
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
     * Set variation
     *
     * @param \AppBundle\Entity\ProduitVariation $variation
     *
     * @return ProduitCommande
     */
    public function setVariation(\AppBundle\Entity\ProduitVariation $variation = null) {
        $this->variation = $variation;

        return $this;
    }

    /**
     * Get variation
     *
     * @return \AppBundle\Entity\ProduitVariation
     */
    public function getVariation() {
        return $this->variation;
    }

    /**
     * Set prixUnitaire
     *
     * @param string $prixUnitaire
     *
     * @return ProduitCommande
     */
    public function setPrixUnitaire($prixUnitaire) {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * Get prixUnitaire
     *
     * @return string
     */
    public function getPrixUnitaire() {
        return $this->prixUnitaire;
    }

    public function getSousTotal() {
        return $this->prixUnitaire * $this->quantite;
    }

}
