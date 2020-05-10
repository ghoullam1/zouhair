<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Commande {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=25, unique=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="remise", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $remise;

    /**
     * @var string
     *
     * @ORM\Column(name="frais_livraison", type="decimal", precision=10, scale=2)
     */
    private $fraisLivraison;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCommande", type="datetimetz")
     */
    private $dateCommande;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitCommande", mappedBy="commande", cascade={"persist","remove"})
     */
    private $produits;

    /**
     * @ORM\ManyToOne(targetEntity="Coupon",cascade={"persist"})
     * @ORM\JoinColumn(name="coupon_id", referencedColumnName="id")
     */
    private $coupon;

    /**
     * @ORM\ManyToOne(targetEntity="ModeLivraison",inversedBy="commandes",cascade={"persist"})
     * @ORM\JoinColumn(name="mode_livraison_id", referencedColumnName="id", nullable=true)
     */
    private $modeLivraison;

    /**
     * @ORM\ManyToOne(targetEntity="Client",inversedBy="commandes")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CommandeHistorique", mappedBy="commande", cascade={"persist", "remove"})
     * @ORM\OrderBy({"dateStatut" = "DESC"})
     */
    private $historiques;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="commande", cascade={"persist","remove"})
     * @ORM\OrderBy({"dateCommentaire" = "DESC"})
     */
    private $commentaires;

    function __construct() {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->historiques = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set reference
     *
     * @param string $reference
     *
     * @return Commande
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     * Set total
     *
     * @param string $total
     *
     * @return Commande
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande) {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande() {
        return $this->dateCommande;
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitCommande $produit
     *
     * @return Commande
     */
    public function addProduit(\AppBundle\Entity\ProduitCommande $produit) {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitCommande $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitCommande $produit) {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits() {
        return $this->produits;
    }

    /**
     * Set coupon
     *
     * @param \AppBundle\Entity\Coupon $coupon
     *
     * @return Commande
     */
    public function setCoupon(\AppBundle\Entity\Coupon $coupon = null) {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return \AppBundle\Entity\Coupon
     */
    public function getCoupon() {
        return $this->coupon;
    }

    /**
     * Set modeLivraison
     *
     * @param \AppBundle\Entity\ModeLivraison $modeLivraison
     *
     * @return Commande
     */
    public function setModeLivraison(\AppBundle\Entity\ModeLivraison $modeLivraison = null) {
        $this->modeLivraison = $modeLivraison;

        return $this;
    }

    /**
     * Get modeLivraison
     *
     * @return \AppBundle\Entity\ModeLivraison
     */
    public function getModeLivraison() {
        return $this->modeLivraison;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Commande
     */
    public function setClient(\AppBundle\Entity\Client $client = null) {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * Add historique
     *
     * @param \AppBundle\Entity\CommandeHistorique $historique
     *
     * @return Commande
     */
    public function addHistorique(\AppBundle\Entity\CommandeHistorique $historique) {
        $this->historiques[] = $historique;

        return $this;
    }

    /**
     * Remove historique
     *
     * @param \AppBundle\Entity\CommandeHistorique $historique
     */
    public function removeHistorique(\AppBundle\Entity\CommandeHistorique $historique) {
        $this->historiques->removeElement($historique);
    }

    /**
     * Get historiques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoriques() {
        return $this->historiques;
    }

    /**
     * Set fraisLivraison
     *
     * @param string $fraisLivraison
     *
     * @return Commande
     */
    public function setFraisLivraison($fraisLivraison) {
        $this->fraisLivraison = $fraisLivraison;

        return $this;
    }

    /**
     * Get fraisLivraison
     *
     * @return string
     */
    public function getFraisLivraison() {
        return $this->fraisLivraison;
    }

    /**
     * Set remise
     *
     * @param string $remise
     *
     * @return Commande
     */
    public function setRemise($remise) {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return string
     */
    public function getRemise() {
        return $this->remise;
    }

    /**
     * Get lastStatut
     *
     * @return StatutCommande
     */
    public function getLastStatut() {

        return $this->historiques->count() > 0 ? $this->historiques->first()->getStatut() : null;
    }

    public function getSousTotal() {
        $sousTotal = 0;
        foreach ($this->produits as $pc) {
            $sousTotal += $pc->getSousTotal();
        }
        return $sousTotal;
    }

    /**
     * @ORM\PrePersist()
     */
    public function generateDateCommande() {
        $this->setDateCommande(new \DateTime("now"));
    }

    /**
     * Add commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     *
     * @return Commande
     */
    public function addCommentaire(\AppBundle\Entity\Commentaire $commentaire) {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\AppBundle\Entity\Commentaire $commentaire) {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

}
