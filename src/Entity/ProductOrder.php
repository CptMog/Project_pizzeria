<?php

namespace App\Entity;

use App\Repository\ProductOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductOrderRepository::class)
 */
class ProductOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="productOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_order;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productOrders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?Order
    {
        return $this->id_order;
    }

    public function setIdOrder(?Order $id_order): self
    {
        $this->id_order = $id_order;

        return $this;
    } 

    public function getIdProduct(): ?Product
    {
        return $this->id_product;
    }

    public function setIdProduct(?Product $id_product): self
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
