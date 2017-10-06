<?php
namespace AppBundle\Model;


use AppBundle\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class UserCart: Panier utilisateur
 * @package AppBundle\Model
 */
class UserCart
{
    /**
     * @var ArrayCollection
     */
    private $produits;

    /**
     * @var int
     */
    private $tva;

    /**
     * @var int
     */
    private $totalTTC;

    /**
     * @var int
     */
    private $totalHT;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @param Produit $produit
     * @return $this
     */
    public function addProduit(Produit $produit)
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
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

    /**
     * @return int
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param int $tva
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
    }

    /**
     * @return int
     */
    public function getTotalTTC()
    {
        return $this->totalTTC;
    }

    /**
     * @param int $sommeTotale
     */
    public function setTotalTTC($sommeTotale)
    {
        $this->totalTTC = $sommeTotale;
    }

    /**
     * @return int
     */
    public function getTotalHT()
    {
        return $this->totalHT;
    }

    /**
     * @param int $totalHT
     */
    public function setTotalHT($totalHT)
    {
        $this->totalHT = $totalHT;
    }
}