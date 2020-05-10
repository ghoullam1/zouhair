<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Produit {

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
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, unique=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock;

    /**
     * @var bool
     *
     * @ORM\Column(name="occasion", type="boolean", nullable=true)
     */
    private $occasion;

    /**
     * @var bool
     *
     * @ORM\Column(name="enSolde", type="boolean", nullable=true)
     */
    private $enSolde;

    /**
     * @var bool
     *
     * @ORM\Column(name="isNew", type="boolean", nullable=true)
     */
    private $isNew;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEndNew", type="datetimetz", nullable=true)
     */
    private $dateEndNew;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_best_product", type="boolean", nullable=true)
     */
    private $isBestProduct;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Categorie", inversedBy="produits")
     * @ORM\JoinTable(name="produit_categorie")
     */
    private $categories;

    /**
     * @var Marque
     *
     * @ORM\ManyToOne(targetEntity="Marque", inversedBy="produits")
     * @ORM\JoinColumn(name="marque_id", referencedColumnName="id",nullable=true)
     */
    private $marque;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="produit", cascade={"all"})
     */
    private $images;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="produit", cascade={"persist","remove"})
     */
    private $commentaires;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitVariation", mappedBy="produit", cascade={"persist","remove"})
     */
    private $variations;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitCommande", mappedBy="produit", cascade={"persist","remove"})
     */
    private $commandes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="produits")
     * @ORM\JoinTable(name="produit_genre")
     */
    private $genres;

    function __construct() {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->variations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Produit
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description ? $this->description : $this->titre;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Produit
     */
    public function setPrix($prix) {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix() {
        return $this->prix;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Produit
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
     * Set occasion
     *
     * @param boolean $occasion
     *
     * @return Produit
     */
    public function setOccasion($occasion) {
        $this->occasion = $occasion;

        return $this;
    }

    /**
     * Get occasion
     *
     * @return bool
     */
    public function getOccasion() {
        return $this->occasion;
    }

    /**
     * Set enSolde
     *
     * @param boolean $enSolde
     *
     * @return Produit
     */
    public function setEnSolde($enSolde) {
        $this->enSolde = $enSolde;

        return $this;
    }

    /**
     * Get enSolde
     *
     * @return bool
     */
    public function getEnSolde() {
        return $this->enSolde;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     *
     * @return Produit
     */
    public function setIsNew($isNew) {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return bool
     */
    public function getIsNew() {
        return $this->isNew;
    }

    /**
     * Set dateEndNew
     *
     * @param \DateTime $dateEndNew
     *
     * @return Produit
     */
    public function setDateEndNew($dateEndNew) {
        $this->dateEndNew = $dateEndNew;

        return $this;
    }

    /**
     * Get dateEndNew
     *
     * @return \DateTime
     */
    public function getDateEndNew() {
        return $this->dateEndNew;
    }

    /**
     * Set isBestProduct
     *
     * @param boolean $isBestProduct
     *
     * @return Produit
     */
    public function setIsBestProduct($isBestProduct) {
        $this->isBestProduct = $isBestProduct;

        return $this;
    }

    /**
     * Get isBestProduct
     *
     * @return boolean
     */
    public function getIsBestProduct() {
        return $this->isBestProduct;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Categorie $category
     *
     * @return Produit
     */
    public function addCategory(\AppBundle\Entity\Categorie $category) {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Categorie $category
     */
    public function removeCategory(\AppBundle\Entity\Categorie $category) {
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

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Produit
     */
    public function addImage(\AppBundle\Entity\Image $image) {
        $this->images[] = $image;
        $image->setProduit($this);
        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\Image $image
     */
    public function removeImage(\AppBundle\Entity\Image $image) {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Add commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     *
     * @return Produit
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

    /**
     * Add variation
     *
     * @param \AppBundle\Entity\produitVariation $variation
     *
     * @return Produit
     */
    public function addVariation(\AppBundle\Entity\produitVariation $variation) {
        $this->variations[] = $variation;
        $variation->setProduit($this);
        return $this;
    }

    /**
     * Remove variation
     *
     * @param \AppBundle\Entity\produitVariation $variation
     */
    public function removeVariation(\AppBundle\Entity\produitVariation $variation) {
        $this->variations->removeElement($variation);
    }

    /**
     * Get variations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariations() {
        return $this->variations;
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\ProduitCommande $commande
     *
     * @return Produit
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

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Produit
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
     * Add genre
     *
     * @param \AppBundle\Entity\Genre $genre
     *
     * @return Produit
     */
    public function addGenre(\AppBundle\Entity\Genre $genre) {
        $this->genres[] = $genre;

        return $this;
    }

    /**
     * Remove genre
     *
     * @param \AppBundle\Entity\Genre $genre
     */
    public function removeGenre(\AppBundle\Entity\Genre $genre) {
        $this->genres->removeElement($genre);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenres() {
        return $this->genres;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setProductToImageAndVariation() {
        foreach ($this->images as $image) {
            $image->setProduit($this);
        }
        foreach ($this->variations as $variation) {
            $variation->setProduit($this);
        }
    }

    function encoreNouveau() {
        if ($this->isNew and $this->dateEndNew > new \Datetime("now"))
            return true;
        else
            return false;
    }

    function categoriesToString() {
        $categories = "";
        foreach ($this->categories as $categorie) {
            $categories .= " - " . $categorie->getLibelle();
        }
        return $categories;
    }

    function genresToString() {
        $genres = "";
        foreach ($this->genres as $genre) {
            $genres .= " - " . $genre->getLibelle();
        }
        return $genres;
    }

    /**
     * Get firstImage
     *
     * @return Image
     */
    public function getFirstImage() {
        return $this->images->count() > 0 ? $this->images->get(0) : $this->getNoImage();
    }

    /**
     * Get noImage
     *
     * @return Image
     */
    public function getNoImage() {
        $image = new Image();
        $image
                ->setNom("no_image")
                ->setExtension("png")
        ;
        return $image;
    }

    function getNote() {
        $count = $moyen = 0;
        foreach ($this->commentaires as $commentaire) {
            $count++;
            $moyen += $commentaire->getNote();
        }
        return $count == 0 ? 0 : $moyen / $count;
    }

    /**
     * Set marque
     *
     * @param \AppBundle\Entity\Marque $marque
     *
     * @return Produit
     */
    public function setMarque(\AppBundle\Entity\Marque $marque = null) {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return \AppBundle\Entity\Marque
     */
    public function getMarque() {
        return $this->marque;
    }

    function disponibilite() {
        return $this->stock > 0 ? "En stock" : "En rupture de stock";
    }

    function getGenresIDs() {
        $ids = array();
        foreach ($this->genres as $genre) {
            $ids[] = $genre->getId();
        }
        return $ids;
    }

    function getCategoriesIDs() {
        $ids = array();
        foreach ($this->categories as $categorie) {
            $ids[] = $categorie->getId();
        }
        return $ids;
    }

}
