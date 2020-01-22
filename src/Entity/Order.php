<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $order_date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethods", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment_method_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeliveryMethods", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delivery_method_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeliveryStatus", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delivery_status_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentStatus", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment_status_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderStatus", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_status_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductOrder", mappedBy="order_id")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostNumber", mappedBy="order_id")
     */
    private $postNumbers;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->postNumbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): self
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPaymentMethodId(): ?PaymentMethods
    {
        return $this->payment_method_id;
    }

    public function setPaymentMethodId(?PaymentMethods $payment_method_id): self
    {
        $this->payment_method_id = $payment_method_id;

        return $this;
    }

    public function getDeliveryMethodId(): ?DeliveryMethods
    {
        return $this->delivery_method_id;
    }

    public function setDeliveryMethodId(?DeliveryMethods $delivery_method_id): self
    {
        $this->delivery_method_id = $delivery_method_id;

        return $this;
    }

    public function getDeliveryStatusId(): ?DeliveryStatus
    {
        return $this->delivery_status_id;
    }

    public function setDeliveryStatusId(?DeliveryStatus $delivery_status_id): self
    {
        $this->delivery_status_id = $delivery_status_id;

        return $this;
    }

    public function getPaymentStatusId(): ?PaymentStatus
    {
        return $this->payment_status_id;
    }

    public function setPaymentStatusId(?PaymentStatus $payment_status_id): self
    {
        $this->payment_status_id = $payment_status_id;

        return $this;
    }

    public function getOrderStatusId(): ?OrderStatus
    {
        return $this->order_status_id;
    }

    public function setOrderStatusId(?OrderStatus $order_status_id): self
    {
        $this->order_status_id = $order_status_id;

        return $this;
    }

    /**
     * @return Collection|ProductOrder[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductOrder $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setOrderId($this);
        }

        return $this;
    }

    public function removeProduct(ProductOrder $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getOrderId() === $this) {
                $product->setOrderId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostNumber[]
     */
    public function getPostNumbers(): Collection
    {
        return $this->postNumbers;
    }

    public function addPostNumber(PostNumber $postNumber): self
    {
        if (!$this->postNumbers->contains($postNumber)) {
            $this->postNumbers[] = $postNumber;
            $postNumber->setOrderId($this);
        }

        return $this;
    }

    public function removePostNumber(PostNumber $postNumber): self
    {
        if ($this->postNumbers->contains($postNumber)) {
            $this->postNumbers->removeElement($postNumber);
            // set the owning side to null (unless already changed)
            if ($postNumber->getOrderId() === $this) {
                $postNumber->setOrderId(null);
            }
        }

        return $this;
    }
}
