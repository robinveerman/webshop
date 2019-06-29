<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReactiesRepository")
 */
class Reacties
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $user;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $text;

	/**
	 * @ORM\Column(type="integer", length=3)
	 */
	private $rating;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $ipAdres;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Product")
	 */
	private $product;

	/**
	 * @ORM\Column(type="datetime", length=255)
	 */
	private $timestamp;

	public function getId(): ?int
	{
		return $this->id;
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

	public function getText(): ?string
	{
		return $this->text;
	}

	public function setText(string $text): self
	{
		$this->text = $text;

		return $this;
	}

	public function getIpAdres(): ?string
	{
		return $this->ipAdres;
	}

	public function setIpAdres(string $ipAdres): self
	{
		$this->ipAdres = $ipAdres;

		return $this;
	}

	public function getProduct(): ?Product
	{
		return $this->product;
	}

	public function setProduct( $product)
	{
		$this->product = $product;

		return $this;
	}

	public function getTimestamp(): ?\DateTimeInterface
	{
		return $this->timestamp;
	}

	public function setTimestamp(\DateTimeInterface $timestamp): self
	{
		$this->timestamp = $timestamp;

		return $this;
	}

	public function getRating(): ?int
	{
		return $this->rating;
	}

	public function setRating(int $rating): self
	{
		$this->rating = $rating;

		return $this;
	}
}