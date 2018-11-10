<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $barcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\Column(type="integer")
     */
    private $vatId;

	/**
	 * @var VATClass
	 * @ORM\ManyToOne(targetEntity="App\Entity\VATClass")
	 * @ORM\JoinColumn(name="vat_id", referencedColumnName="id")
	 */
    private $vat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getVatId(): ?int
    {
        return $this->vatId;
    }

    public function setVatId(int $vatId): self
    {
        $this->vatId = $vatId;

        return $this;
    }

	public function getVat(): VATClass
	{
		return $this->vat;
	}

	public function setVat(VATClass $vat)
	{
		$this->vat = $vat;
	}
}
