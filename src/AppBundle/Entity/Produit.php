<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Thenbsp\CartBundle\Core\EntityInterface;

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
    private $quantiteAlerte;
    /**
     * @var int
     *
     * @ORM\Column(name="qte_stock", type="integer")
     */
    private $quantiteStock;
    /**
     * @var int
     *
     * @ORM\Column(name="prix_unitaire", type="decimal")
     */
    private $prixUnitaire;

    /**
     * @var int
     */
    private $quantiteAchetee;
    
    /**
     * @var CategorieProduit
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategorieProduit", inversedBy="produits", cascade={"persist"})
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProduitFacture", mappedBy="produit")
     */
    private $facturesDuProduit;


    public function __construct(
        CategorieProduit $categorie = null, 
        $numero = null, 
        $designation = null, 
        $qauntiteAlerte = null, 
        $qauntiteStock = null,
        $prixUnitaire = null
    )
    {
        if (null !== $categorie) {
            $this->setCategorie($categorie);
            $this->setNumero($numero);
            $this->setDesignation($designation);
            $this->setQuantiteAlerte($qauntiteAlerte);
            $this->setQuantiteStock($qauntiteStock);
            $this->setPrixUnitaire($prixUnitaire);
        }
        
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
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param CategorieProduit $category
     * @return $this
     */
    public function setCategorie(CategorieProduit $category)
    {
        $this->categorie = $category;
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
    public function getQuantiteAlerte()
    {
        return $this->quantiteAlerte;
    }

    /**
     * @param $qauntiteAlerte
     * @return $this
     */
    public function setQuantiteAlerte($qauntiteAlerte)
    {
        $this->quantiteAlerte = $qauntiteAlerte;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantiteStock()
    {
        return $this->quantiteStock;
    }

    /**
     * @param $qauntiteStock
     * @return $this
     */
    public function setQuantiteStock($qauntiteStock)
    {
        $this->quantiteStock = $qauntiteStock;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantiteAchetee()
    {
        return $this->quantiteAchetee;
    }

    /**
     * @param int $quantiteAchetee
     */
    public function setQuantiteAchetee($quantiteAchetee)
    {
        $this->quantiteAchetee = $quantiteAchetee;
    }

    /**
     * @return int
     */
    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    /**
     * @param int $prixUnitaire
     */
    public function setPrixUnitaire($prixUnitaire)
    {
        $this->prixUnitaire = $prixUnitaire;
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

//    /**
//     * String representation of object
//     * @link http://php.net/manual/en/serializable.serialize.php
//     * @return string the string representation of the object or null
//     * @since 5.1.0
//     */
//    public function serialize()
//    {
//        return serialize(
//            [
//                $this->id,
//                $this->designation,
//                $this->quantiteAchetee,
//            ]
//        );
//    }
//
//    /**
//     * Constructs the object
//     * @link http://php.net/manual/en/serializable.unserialize.php
//     * @param string $serialized <p>
//     * The string representation of the object.
//     * </p>
//     * @return void
//     * @since 5.1.0
//     */
//    public function unserialize($serialized)
//    {
//        $data = unserialize($serialized);
//        list(
//            $this->id,
//            $this->designation,
//            $this->quantiteAchetee,
//            ) = $data;
//
//    }
}

