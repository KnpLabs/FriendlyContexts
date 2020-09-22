<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Product
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
    public $name;

    /**
     * @ORM\Column(type="decimal")
     */
    public $price;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     */
    public $user;
}
