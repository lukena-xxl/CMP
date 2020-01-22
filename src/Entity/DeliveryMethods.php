<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryMethodsRepository")
 */
class DeliveryMethods
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $short_description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $commission;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_visible;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $api_key_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $api_key_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $api_key_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note_api_key_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note_api_key_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note_api_key_3;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="deliveries")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="delivery_method_id")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostNumber", mappedBy="delivery_method_id")
     */
    private $postNumbers;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->postNumbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(?float $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

        return $this;
    }

    public function getApiKey1(): ?string
    {
        return $this->api_key_1;
    }

    public function setApiKey1(?string $api_key_1): self
    {
        $this->api_key_1 = $api_key_1;

        return $this;
    }

    public function getApiKey2(): ?string
    {
        return $this->api_key_2;
    }

    public function setApiKey2(?string $api_key_2): self
    {
        $this->api_key_2 = $api_key_2;

        return $this;
    }

    public function getApiKey3(): ?string
    {
        return $this->api_key_3;
    }

    public function setApiKey3(?string $api_key_3): self
    {
        $this->api_key_3 = $api_key_3;

        return $this;
    }

    public function getNoteApiKey1(): ?string
    {
        return $this->note_api_key_1;
    }

    public function setNoteApiKey1(?string $note_api_key_1): self
    {
        $this->note_api_key_1 = $note_api_key_1;

        return $this;
    }

    public function getNoteApiKey2(): ?string
    {
        return $this->note_api_key_2;
    }

    public function setNoteApiKey2(?string $note_api_key_2): self
    {
        $this->note_api_key_2 = $note_api_key_2;

        return $this;
    }

    public function getNoteApiKey3(): ?string
    {
        return $this->note_api_key_3;
    }

    public function setNoteApiKey3(?string $note_api_key_3): self
    {
        $this->note_api_key_3 = $note_api_key_3;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addDelivery($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeDelivery($this);
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setDeliveryMethodId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getDeliveryMethodId() === $this) {
                $order->setDeliveryMethodId(null);
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
            $postNumber->setDeliveryMethodId($this);
        }

        return $this;
    }

    public function removePostNumber(PostNumber $postNumber): self
    {
        if ($this->postNumbers->contains($postNumber)) {
            $this->postNumbers->removeElement($postNumber);
            // set the owning side to null (unless already changed)
            if ($postNumber->getDeliveryMethodId() === $this) {
                $postNumber->setDeliveryMethodId(null);
            }
        }

        return $this;
    }
}
