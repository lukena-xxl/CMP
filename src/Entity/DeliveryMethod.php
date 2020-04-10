<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryMethodRepository")
 */
class DeliveryMethod implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $short_description;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $key_1;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $key_2;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $key_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note_key_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note_key_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note_key_3;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="delivery")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="delivery_method")
     */
    private $orders;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
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

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

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

    public function getKey1(): ?string
    {
        return $this->key_1;
    }

    public function setKey1(?string $key_1): self
    {
        $this->key_1 = $key_1;

        return $this;
    }

    public function getKey2(): ?string
    {
        return $this->key_2;
    }

    public function setKey2(?string $key_2): self
    {
        $this->key_2 = $key_2;

        return $this;
    }

    public function getKey3(): ?string
    {
        return $this->key_3;
    }

    public function setKey3(?string $key_3): self
    {
        $this->key_3 = $key_3;

        return $this;
    }

    public function getNoteKey1(): ?string
    {
        return $this->note_key_1;
    }

    public function setNoteKey1(?string $note_key_1): self
    {
        $this->note_key_1 = $note_key_1;

        return $this;
    }

    public function getNoteKey2(): ?string
    {
        return $this->note_key_2;
    }

    public function setNoteKey2(?string $note_key_2): self
    {
        $this->note_key_2 = $note_key_2;

        return $this;
    }

    public function getNoteKey3(): ?string
    {
        return $this->note_key_3;
    }

    public function setNoteKey3(?string $note_key_3): self
    {
        $this->note_key_3 = $note_key_3;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

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
     * @return Collection|Orders[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setDeliveryMethod($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getDeliveryMethod() === $this) {
                $order->setDeliveryMethod(null);
            }
        }

        return $this;
    }
}
