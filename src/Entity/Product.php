<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="integer")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

    /**
     * @ORM\Column(type="float")
     */
    private $prijs;

	/**
	 * @ORM\ManyToOne(targetEntity="Merk")
	 */
	private $merk;

	/**
	 * @ORM\Column(type="float", name="actieprijs")
	 */
	private $actieprijs;

	/**
	 * @ORM\Column(type="integer", name="voorraad", nullable=true)
	 */
	private $voorraad;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Afbeeldingen")
     */
    private $imagepath;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $maat;

	/**
	 * @ORM\Column(type="text", length=255, nullable=true)
	 */
	private $omschrijving;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Product")
	 */
	private $relatedProducts;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Categorie")
	 */
	private $categorie;

    public function __construct()
    {
        $this->relatedProducts = new ArrayCollection();
        $this->imagepath = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getNaam(): ?string
    {
        return $this->naam;
    }

    public function setNaam(string $naam): self
    {
        $this->naam = $naam;

        return $this;
    }

    public function getPrijs(): ?float
    {
        return $this->prijs;
    }

    public function setPrijs(float $prijs): self
    {
        $this->prijs = $prijs;

        return $this;
    }

    public function getImagepath()
    {
        return $this->imagepath;
    }

    public function setImagepath(?string $imagepath): self
    {
        $this->imagepath = $imagepath;

        return $this;
    }
    public function __toString() {
	  return (string) $this->getCode() . ' -> ' . $this->getNaam() . ' (' . $this->getMaat() . ')';
    }

    public function getMaat(): ?string
    {
        return $this->maat;
    }

    public function setMaat(?string $maat): self
    {
        $this->maat = $maat;

        return $this;
    }

    public function getOmschrijving(): ?string
    {
        return $this->omschrijving;
    }

    public function setOmschrijving(?string $omschrijving): self
    {
        $this->omschrijving = $omschrijving;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getRelatedProducts(): Collection
    {
        return $this->relatedProducts;
    }

    public function addRelatedProduct(Product $relatedProduct): self
    {
        if (!$this->relatedProducts->contains($relatedProduct)) {
            $this->relatedProducts[] = $relatedProduct;
        }

        return $this;
    }

    public function removeRelatedProduct(Product $relatedProduct): self
    {
        if ($this->relatedProducts->contains($relatedProduct)) {
            $this->relatedProducts->removeElement($relatedProduct);
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function addImagepath(Afbeeldingen $imagepath): self
    {
        if (!$this->imagepath->contains($imagepath)) {
            $this->imagepath[] = $imagepath;
        }

        return $this;
    }

    public function removeImagepath(Afbeeldingen $imagepath): self
    {
        if ($this->imagepath->contains($imagepath)) {
            $this->imagepath->removeElement($imagepath);
        }

        return $this;
    }

    public function getActieprijs(): ?float
    {
        return $this->actieprijs;
    }

    public function setActieprijs(float $actieprijs): self
    {
        $this->actieprijs = $actieprijs;

        return $this;
    }

    public function getVoorraad(): ?int
    {
        return $this->voorraad;
    }

    public function setVoorraad(int $voorraad): self
    {
        $this->voorraad = $voorraad;

        return $this;
    }

    public function getMerk(): ?Merk
    {
        return $this->merk;
    }

    public function setMerk(?Merk $merk): self
    {
        $this->merk = $merk;

        return $this;
    }
}
