<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KortingscodeRepository")
 */
class Kortingscode
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
     * @ORM\Column(type="float")
     */
    private $percentage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $einddatum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

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

    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function getEinddatum(): ?\DateTimeInterface
    {
        return $this->einddatum;
    }

    public function setEinddatum(?\DateTimeInterface $einddatum): self
    {
        $this->einddatum = $einddatum;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
