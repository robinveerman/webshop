<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MerkRepository")
 */
class Merk
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
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $omschrijving;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagepath;

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

    public function getImagepath(): ?string
    {
        return $this->imagepath;
    }

    public function setImagepath(string $imagepath): self
    {
        $this->imagepath = $imagepath;

        return $this;
    }
    public function __toString() {
	    return $this->getNaam();
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
}
