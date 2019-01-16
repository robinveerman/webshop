<?php
// src/Entity/User.php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\NAW")
	 */
	private $naw;

	public function __construct()
         	{
         		parent::__construct();
         		// your own logic
         	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNaw(): ?NAW
    {
        return $this->naw;
    }

    public function setNaw(?NAW $naw): self
    {
        $this->naw = $naw;

        return $this;
    }
}