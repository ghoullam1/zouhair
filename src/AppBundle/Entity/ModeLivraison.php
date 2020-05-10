<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModeLivraison
 *
 * @ORM\Table(name="mode_livraison")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModeLivraisonRepository")
 */
class ModeLivraison {

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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="fraisLivraison", type="decimal", precision=10, scale=2)
     */
    private $fraisLivraison;

    /**
     * @ORM\ManyToOne(targetEntity="Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ville_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $ville;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * 
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="modeLivraison")
     */
    private $commandes;

    public function __construct() {
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return ModeLivraison
     */
    public function setLibelle($libelle) {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle() {
        return $this->libelle;
    }

    /**
     * Set fraisLivraison
     *
     * @param string $fraisLivraison
     *
     * @return ModeLivraison
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
     * Set ville
     *
     * @param \AppBundle\Entity\Ville $ville
     *
     * @return ModeLivraison
     */
    public function setVille(\AppBundle\Entity\Ville $ville = null) {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \AppBundle\Entity\Ville
     */
    public function getVille() {
        return $this->ville;
    }


    /**
     * Add commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return ModeLivraison
     */
    public function addCommande(\AppBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \AppBundle\Entity\Commande $commande
     */
    public function removeCommande(\AppBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }
}
