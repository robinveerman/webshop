<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AfbeeldingenRepository")
 */
class Afbeeldingen
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
    private $imagepath;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $omschrijving;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alttekst;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $timestamp;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOmschrijving(): ?string
    {
        return $this->omschrijving;
    }

    public function setOmschrijving(string $omschrijving): self
    {
        $this->omschrijving = $omschrijving;

        return $this;
    }

    public function getAlttekst(): ?string
    {
        return $this->alttekst;
    }

    public function setAlttekst(string $alttekst): self
    {
        $this->alttekst = $alttekst;

        return $this;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp( $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
    public function __toString() {
	   return $this->getOmschrijving();
    }
}
