<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenreRepository")
 */
class Genre {

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
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="genres")
     */
    private $produits;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Categorie", mappedBy="genres")
     */
    private $categories;

    function __construct() {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Genre
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Genre
     */
    public function setOrdre($ordre) {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre() {
        return $this->ordre;
    }

    /**
     * Add produit
     *
     * @param Produit $produit
     *
     * @return Genre
     */
    public function addProduit(Produit $produit) {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param Produit $produit
     */
    public function removeProduit(Produit $produit) {
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
     * Add category
     *
     * @param Categorie $category
     *
     * @return Genre
     */
    public function addCategory(Categorie $category) {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param Categorie $category
     */
    public function removeCategory(Categorie $category) {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories() {
        return $this->categories;
    }

}
