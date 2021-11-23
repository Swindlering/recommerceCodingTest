<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Product;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->created = new \DateTime('now');
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer_email;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * One Order has many Products (mobiles)
     * @ORM\OneToMany(targetEntity="Product", mappedBy="order")
     */
    private $mobiles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): self
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function getMobiles(): ?ArrayCollection
    {
        return $this->mobiles;
    }

    public function setMobiles(ArrayCollection $mobiles): self
    {
        $this->mobiles = $mobiles;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'customer_email' => $this->getCustomerEmail(),
            'amount' => $this->getAmount(),
            'created' => $this->getCreated(),
            'mobiles' => $this->getMobiles(),
        ];
    }
}
