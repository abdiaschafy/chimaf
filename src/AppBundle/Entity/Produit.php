<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\Column(name="designation", type="string", length=255)
     */
    private $designation;
    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=30)
     */
    private $numero;
    /**
     * @var int
     *
     * @ORM\Column(name="qte_alerte", type="integer")
     */
    private $qauntiteAlerte;
    /**
     * @var int
     *
     * @ORM\Column(name="qte_stock", type="integer")
     */
    private $qauntiteStock;
    /**
     * @var CategorieProduit
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategorieProduit", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $category;
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProduitFacture", mappedBy="produit")
     */
    private $facturesDuProduit;


    public function __construct(CategorieProduit $categorie, $numero, $designation, $qauntiteAlerte, $qauntiteStock)
    {
        $this->setCategory($categorie);
        $this->setNumero($numero);
        $this->setDesignation($designation);
        $this->setQauntiteAlerte($qauntiteAlerte);
        $this->setQauntiteStock($qauntiteStock);
        $this->facturesDuProduit = new ArrayCollection();
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
     * Set designation
     *
     * @param string $designation
     *
     * @return Produit
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @return CategorieProduit
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param CategorieProduit $category
     * @return $this
     */
    public function setCategory(CategorieProduit $category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return int
     */
    public function getQauntiteAlerte()
    {
        return $this->qauntiteAlerte;
    }

    /**
     * @param $qauntiteAlerte
     * @return $this
     */
    public function setQauntiteAlerte($qauntiteAlerte)
    {
        $this->qauntiteAlerte = $qauntiteAlerte;
        return $this;
    }

    /**
     * @return int
     */
    public function getQauntiteStock()
    {
        return $this->qauntiteStock;
    }

    /**
     * @param $qauntiteStock
     * @return $this
     */
    public function setQauntiteStock($qauntiteStock)
    {
        $this->qauntiteStock = $qauntiteStock;
        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getFacturesDuProduit()
    {
        return $this->facturesDuProduit;
    }

    /**
     * @param ArrayCollection $facturesDuProduit
     */
    public function setFacturesDuProduit(ArrayCollection $facturesDuProduit)
    {
        $this->facturesDuProduit = $facturesDuProduit;
    }
}

