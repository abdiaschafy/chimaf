<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieProduit
 *
 * @ORM\Table(name="categorie_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategorieProduitRepository")
 */
class CategorieProduit
{
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * O@var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Produit", mappedBy="category")
     */
    private $produits;


    public function __construct($nom)
    {
        $this->nom = $nom;
        $this->produits = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return CategorieProduit
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getProduits()
    {
        return $this->produits;
    }
    
    /**
     * @param ArrayCollection $produits
     * @return $this
     */
    public function setProduits(ArrayCollection $produits)
    {
        $this->produits = $produits;
        return $this;
    }

    /**
     * @param Produit $produit
     * @return $this
     */
    public function addProduit(Produit $produit)
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[]  = $produit;
        }
        return $this;
    }

    /**
     * @param Produit $produit
     * @return $this
     */
    public function removeProduit(Produit $produit)
    {
        if (!$this->produits->isEmpty()) {
            $this->produits->removeElement($produit);
        }
        return $this;
    }
}

