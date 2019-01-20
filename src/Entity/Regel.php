<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegelRepository")
 */
class Regel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Factuur")
     */
    private $factuurId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $productId;

    /**
     * @ORM\Column(type="integer")
     */
    private $aantal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFactuurId(): ?Factuur
    {
        return $this->factuurId;
    }

    public function setFactuurId(?Factuur $factuurId): self
    {
        $this->factuurId = $factuurId;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->productId;
    }

    public function setProductId(?Product $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getAantal(): ?int
    {
        return $this->aantal;
    }

    public function setAantal(int $aantal): self
    {
        $this->aantal = $aantal;

        return $this;
    }
}
