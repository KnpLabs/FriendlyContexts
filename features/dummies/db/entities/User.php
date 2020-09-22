<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User
{
    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    public $id;

    /**
     * @ORM\Column
     */
    public $login;

    /**
     * @ORM\Column
     */
    public $firstname;

    /**
     * @ORM\Column
     */
    public $lastname;

    /**
     * @ORM\Column
     */
    public $email;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="user")
     */
    public $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
}
