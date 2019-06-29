<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naam;

	/**
	 * @ORM\OneToMany(targetEntity="Product", mappedBy="categorie")
	 */
	private $producten;

    public function __construct()
    {
        $this->producten = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function __toString() {
	    return (string) $this->getNaam();
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducten(): Collection
    {
        return $this->producten;
    }

    public function addProducten(Product $producten): self
    {
        if (!$this->producten->contains($producten)) {
            $this->producten[] = $producten;
            $producten->setCategorie($this);
        }

        return $this;
    }

    public function removeProducten(Product $producten): self
    {
        if ($this->producten->contains($producten)) {
            $this->producten->removeElement($producten);
            // set the owning side to null (unless already changed)
            if ($producten->getCategorie() === $this) {
                $producten->setCategorie(null);
            }
        }

        return $this;
    }
}
