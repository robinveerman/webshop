<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 */
class Settings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $AddCartAjax = 0;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $ClearCart = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddCartAjax(): ?bool
    {
        return $this->AddCartAjax;
    }

    public function setAddCartAjax(bool $AddCartAjax): self
    {
        $this->AddCartAjax = $AddCartAjax;

        return $this;
    }

    public function getClearCart(): ?bool
    {
        return $this->ClearCart;
    }

    public function setClearCart(bool $ClearCart): self
    {
        $this->ClearCart = $ClearCart;

        return $this;
    }
}
