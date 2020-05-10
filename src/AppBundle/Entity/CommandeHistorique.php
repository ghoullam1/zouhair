<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeHistorique
 *
 * @ORM\Table(name="commande_historique")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeHistoriqueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CommandeHistorique {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStatut", type="datetimetz")
     */
    private $dateStatut;

    /**
     * @var Commande
     * @ORM\ManyToOne(targetEntity="Commande", inversedBy="historiques")
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     */
    private $commande;

    /**
     * @var SatutCommande
     * @ORM\ManyToOne(targetEntity="StatutCommande", inversedBy="commandes")
     * @ORM\JoinColumn(name="statut_id", referencedColumnName="id")
     */
    private $statut;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set dateStatut
     *
     * @param \DateTime $dateStatut
     *
     * @return CommandeHistorique
     */
    public function setDateStatut($dateStatut) {
        $this->dateStatut = $dateStatut;

        return $this;
    }

    /**
     * Get dateStatut
     *
     * @return \DateTime
     */
    public function getDateStatut() {
        return $this->dateStatut;
    }

    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return CommandeHistorique
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
     * Set statut
     *
     * @param \AppBundle\Entity\StatutCommande $statut
     *
     * @return CommandeHistorique
     */
    public function setStatut(\AppBundle\Entity\StatutCommande $statut = null) {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \AppBundle\Entity\StatutCommande
     */
    public function getStatut() {
        return $this->statut;
    }

    /**
     * @ORM\PrePersist()
     */
    public function generateDateStatut() {
        $this->setDateStatut(new \DateTime("now"));
    }

}
