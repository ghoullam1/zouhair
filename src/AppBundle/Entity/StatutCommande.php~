<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatutCommande
 *
 * @ORM\Table(name="statut_commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatutCommandeRepository")
 */
class StatutCommande {

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
     * @var bool
     *
     * @ORM\Column(name="estPaye", type="boolean", nullable=true)
     */
    private $estPaye;

    /**
     * @var bool
     *
     * @ORM\Column(name="estlivre", type="boolean", nullable=true)
     */
    private $estLivre;

    /**
     * @var bool
     *
     * @ORM\Column(name="sendMail", type="boolean", nullable=true)
     */
    private $sendMail;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="text", nullable=true)
     */
    private $template;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="CommandeHistorique", mappedBy="statut")
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return SatutCommande
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
     * Set estPaye
     *
     * @param boolean $estPaye
     *
     * @return SatutCommande
     */
    public function setEstPaye($estPaye) {
        $this->estPaye = $estPaye;

        return $this;
    }

    /**
     * Get estPaye
     *
     * @return bool
     */
    public function getEstPaye() {
        return $this->estPaye;
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\CommandeHistorique $commande
     *
     * @return StatutCommande
     */
    public function addCommande(\AppBundle\Entity\CommandeHistorique $commande) {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \AppBundle\Entity\CommandeHistorique $commande
     */
    public function removeCommande(\AppBundle\Entity\CommandeHistorique $commande) {
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

    /**
     * Set estLivre
     *
     * @param boolean $estLivre
     *
     * @return StatutCommande
     */
    public function setEstLivre($estLivre) {
        $this->estLivre = $estLivre;

        return $this;
    }

    /**
     * Get estLivre
     *
     * @return boolean
     */
    public function getEstLivre() {
        return $this->estLivre;
    }

    /**
     * Set sendMail
     *
     * @param boolean $sendMail
     *
     * @return StatutCommande
     */
    public function setSendMail($sendMail) {
        $this->sendMail = $sendMail;

        return $this;
    }

    /**
     * Get sendMail
     *
     * @return boolean
     */
    public function getSendMail() {
        return $this->sendMail;
    }


    /**
     * Set template
     *
     * @param string $template
     *
     * @return StatutCommande
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
