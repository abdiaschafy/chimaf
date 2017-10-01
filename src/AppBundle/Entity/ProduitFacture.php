<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitFacture
 *
 * @ORM\Table(name="produit_facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitFactureRepository")
 */
class ProduitFacture
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
     * @var integer
     *
     * @ORM\Column(name="prix", type="decimal")
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produit", inversedBy="facturesDuProduit")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Facture", inversedBy="produitsDeLaFacture")
     * @ORM\JoinColumn(name="facture_id", referencedColumnName="id")
     */
    private $facture;
    
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
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param int $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    /**
     * @return Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param Produit $produit
     * @return $this
     */
    public function setProduit(Produit $produit)
    {
        $this->produit = $produit;
        return $this;
    }

    /**
     * @return Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * @param Facture $facture
     * @return $this
     */
    public function setFacture(Facture $facture)
    {
        $this->facture = $facture;
        return $this;
    }
}

