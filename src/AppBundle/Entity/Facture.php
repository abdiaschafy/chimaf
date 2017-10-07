<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
 */
class Facture
{
    const CHIMAF = "CHIMAF";
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
     * @ORM\Column(name="numero", type="string", length=10)
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFacture", type="datetime")
     */
    private $dateFacture;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProduitFacture", mappedBy="facture")
     */
    private $produitsDeLaFacture;

    /**
     * @var int
     * @ORM\Column(name="total_ttc", type="decimal", nullable=false, precision=19, scale=2)
     */
    private $totalTtc;

    /**
     * @var int
     * @ORM\Column(name="total_ht", type="decimal", nullable=false, precision=19, scale=2)
     */
    private $totalHt;

    /**
     * @var int
     * @ORM\Column(name="tva", type="decimal", nullable=false, precision=4, scale=1)
     */
    private $tva;

    public function __construct($totalTtc, $totalHt, $tva)
    {
        $this->totalTtc = $totalTtc;
        $this->totalHt = $totalHt;
        $this->tva = $tva;
        $this->produitsDeLaFacture = new ArrayCollection();
        $this->dateFacture = new \DateTime();
        $this->numero = 'CH'.strtoupper(substr(md5(uniqid(self::CHIMAF, true)),0,15));
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
     * Set numero
     *
     * @param string $numero
     *
     * @return Facture
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set dateFacture
     *
     * @param \DateTime $dateFacture
     *
     * @return Facture
     */
    public function setDateFacture($dateFacture)
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    /**
     * Get dateFacture
     *
     * @return \DateTime
     */
    public function getDateFacture()
    {
        return $this->dateFacture;
    }

    /**
     * @return ArrayCollection
     */
    public function getProduitsDeLaFacture()
    {
        return $this->produitsDeLaFacture;
    }

    /**
     * @param ArrayCollection $produitsDeLaFacture
     */
    public function setProduitsDeLaFacture(ArrayCollection $produitsDeLaFacture)
    {
        $this->produitsDeLaFacture = $produitsDeLaFacture;
    }

    /**
     * @return int
     */
    public function getTotalTtc()
    {
        return $this->totalTtc;
    }

    /**
     * @param int $total
     */
    public function setTotalTtc($total)
    {
        $this->totalTtc = $total;
    }

    /**
     * @return int
     */
    public function getTotalHt()
    {
        return $this->totalHt;
    }

    /**
     * @param int $totalHt
     */
    public function setTotalHt($totalHt)
    {
        $this->totalHt = $totalHt;
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
}

