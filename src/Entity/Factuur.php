<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactuurRepository")
 */
class Factuur {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	private $klantId;

	/**
	 * @ORM\Column(type="date")
	 */
	private $datum;

	private $regels;

	public function __construct() {
		$this->regels = new ArrayCollection();
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getKlantId(): ?User {
		return $this->klantId;
	}

	public function setKlantId( ?User $klantId ): self {
		$this->klantId = $klantId;

		return $this;
	}

	public function getDatum(): ?\DateTimeInterface {
		return $this->datum;
	}

	public function setDatum( \DateTimeInterface $datum ): self {
		$this->datum = $datum;

		return $this;
	}

	public function setRegels( $regels ) {
		$this->regels = $regels;
	}

	public function getRegels() {
		return $this->regels;
	}

	public function addRegel( Regel $regel ): self {
		$this->regels = new ArrayCollection();
		$this->regels->add( $regel );

		return $this;
	}

	public function removeRegel( Regel $regel ) {
		$this->regels = new ArrayCollection();
		dump($regel);
		$this->regels->remove( $regel->getId() );
	}


	public function __toString() {
		return (string) $this->getId() . ' -> ' . $this->getKlantId();
	}
}
