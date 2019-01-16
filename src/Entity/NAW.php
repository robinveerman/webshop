<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NAWRepository")
 */
class NAW
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(onDelete="SET NULL")
	 */
	private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Voornaam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Achternaam;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Adres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Postcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Woonplaats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoornaam(): ?string
    {
        return $this->Voornaam;
    }

    public function setVoornaam(string $Voornaam): self
    {
        $this->Voornaam = $Voornaam;

        return $this;
    }

    public function getAchternaam(): ?string
    {
        return $this->Achternaam;
    }

    public function setAchternaam(string $Achternaam): self
    {
        $this->Achternaam = $Achternaam;

        return $this;
    }

    public function getAdres(): ?string
    {
        return $this->Adres;
    }

    public function setAdres(string $Adres): self
    {
        $this->Adres = $Adres;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->Postcode;
    }

    public function setPostcode(string $Postcode): self
    {
        $this->Postcode = $Postcode;

        return $this;
    }

    public function getWoonplaats(): ?string
    {
        return $this->Woonplaats;
    }

    public function setWoonplaats(string $Woonplaats): self
    {
        $this->Woonplaats = $Woonplaats;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
