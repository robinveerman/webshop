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

	/**
	 * @ORM\Column(type="string", name="status")
	 */
	private $status;

	protected $regels;

	/**
	 * @ORM\ManyToMany(targetEntity="Kortingscode", cascade={"persist"})
	 */
	private $kortingscode;

	public function __construct() {
		$this->regels       = new ArrayCollection();
		$this->kortingscode = new ArrayCollection();
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

	/**
	 * @param ArrayCollection $rgls
	 */
	public function setRegels( $regels ) {
		$this->regels = $regels;
	}

	public function getRegels() {
		return $this->regels;
	}


	public function __toString() {
		return (string) $this->getId() . ' -> ' . $this->getKlantId();
	}

	public function getStatus(): ?string {
		return $this->status;
	}

	public function setStatus( string $status ): self {
		$this->status = $status;

		return $this;
	}

	/**
	 * @return Collection|Kortingscode[]
	 */
	public function getKortingscode(): Collection {
		return $this->kortingscode;
	}

	public function addKortingscode( Kortingscode $kortingscode ): self {
		if ( ! $this->kortingscode->contains( $kortingscode ) ) {
			$this->kortingscode[] = $kortingscode;
		}

		return $this;
	}

	public function removeKortingscode( Kortingscode $kortingscode ): self {
		if ( $this->kortingscode->contains( $kortingscode ) ) {
			$this->kortingscode->removeElement( $kortingscode );
		}

		return $this;
	}
}
