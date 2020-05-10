<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;
use ReverseRegex\Exception;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategorieRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Categorie {

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
     * @Assert\NotNull(
     *     message = "Le libellé ne doit pas être null !",
     * )
     * 
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="categories")
     */
    private $produits;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Marque", mappedBy="categories")
     * 
     */
    private $marques;

    /**
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $image;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="categories" , cascade={"persist"})
     * @ORM\JoinTable(name="categorie_genre")
     */
    private $genres;

    public function __construct() {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marques = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Categorie
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Categorie
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return Categorie
     */
    public function addProduit(\AppBundle\Entity\Produit $produit) {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\Produit $produit
     */
    public function removeProduit(\AppBundle\Entity\Produit $produit) {
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
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Categorie
     */
    public function setImage(\AppBundle\Entity\Image $image = null) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return Categorie
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
     * @ORM\PrePersist()
     */
    public function generateSlug() {
        $regex = '^CAT([A-Z]|[a-z]|[0-9]){6}$';
        $lexer = new Lexer($regex);
        $parser = new Parser($lexer, new Scope(), new Scope());
        $generator = $parser->parse()->getResult();
        $rand = rand(10000, 99999);
        $random = new MersenneRandom($rand);
        $slug = '';
        $generator->generate($slug, $random);

        $this->setSlug($slug);
    }

    /**
     * Add genre
     *
     * @param \AppBundle\Entity\Genre $genre
     *
     * @return Categorie
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
     * Add marque
     *
     * @param \AppBundle\Entity\Marque $marque
     *
     * @return Categorie
     */
    public function addMarque(\AppBundle\Entity\Marque $marque)
    {
        $this->marques[] = $marque;

        return $this;
    }

    /**
     * Remove marque
     *
     * @param \AppBundle\Entity\Marque $marque
     */
    public function removeMarque(\AppBundle\Entity\Marque $marque)
    {
        $this->marques->removeElement($marque);
    }

    /**
     * Get marques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarques()
    {
        return $this->marques;
    }
}
