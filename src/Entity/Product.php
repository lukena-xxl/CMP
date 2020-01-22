<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefficient;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount_percentage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $discount_end_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $discount_start_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Availability", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $availability_id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="products")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product_id")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="product_id")
     */
    private $parameters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFilter", mappedBy="product_id")
     */
    private $filters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductOrder", mappedBy="product_id")
     */
    private $orders;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PaymentMethods", inversedBy="products")
     */
    private $payments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DeliveryMethods", inversedBy="products")
     */
    private $deliveries;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->filters = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(?float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getDiscountPercentage(): ?float
    {
        return $this->discount_percentage;
    }

    public function setDiscountPercentage(?float $discount_percentage): self
    {
        $this->discount_percentage = $discount_percentage;

        return $this;
    }

    public function getDiscountEndDate(): ?\DateTimeInterface
    {
        return $this->discount_end_date;
    }

    public function setDiscountEndDate(?\DateTimeInterface $discount_end_date): self
    {
        $this->discount_end_date = $discount_end_date;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(?\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

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

    public function getDiscountStartDate(): ?\DateTimeInterface
    {
        return $this->discount_start_date;
    }

    public function setDiscountStartDate(?\DateTimeInterface $discount_start_date): self
    {
        $this->discount_start_date = $discount_start_date;

        return $this;
    }

    public function getCategoryId(): ?Category
    {
        return $this->category_id;
    }

    public function setCategoryId(?Category $category_id): self
    {
        $this->category_id = $category_id;

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

    public function getCurrencyId(): ?Currency
    {
        return $this->currency_id;
    }

    public function setCurrencyId(?Currency $currency_id): self
    {
        $this->currency_id = $currency_id;

        return $this;
    }

    public function getAvailabilityId(): ?Availability
    {
        return $this->availability_id;
    }

    public function setAvailabilityId(?Availability $availability_id): self
    {
        $this->availability_id = $availability_id;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|ProductImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ProductImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProductId($this);
        }

        return $this;
    }

    public function removeImage(ProductImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProductId() === $this) {
                $image->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductParameter[]
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function addParameter(ProductParameter $parameter): self
    {
        if (!$this->parameters->contains($parameter)) {
            $this->parameters[] = $parameter;
            $parameter->setProductId($this);
        }

        return $this;
    }

    public function removeParameter(ProductParameter $parameter): self
    {
        if ($this->parameters->contains($parameter)) {
            $this->parameters->removeElement($parameter);
            // set the owning side to null (unless already changed)
            if ($parameter->getProductId() === $this) {
                $parameter->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductFilter[]
     */
    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function addFilter(ProductFilter $filter): self
    {
        if (!$this->filters->contains($filter)) {
            $this->filters[] = $filter;
            $filter->setProductId($this);
        }

        return $this;
    }

    public function removeFilter(ProductFilter $filter): self
    {
        if ($this->filters->contains($filter)) {
            $this->filters->removeElement($filter);
            // set the owning side to null (unless already changed)
            if ($filter->getProductId() === $this) {
                $filter->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductOrder[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(ProductOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setProductId($this);
        }

        return $this;
    }

    public function removeOrder(ProductOrder $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getProductId() === $this) {
                $order->setProductId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PaymentMethods[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(PaymentMethods $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
        }

        return $this;
    }

    public function removePayment(PaymentMethods $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
        }

        return $this;
    }

    /**
     * @return Collection|DeliveryMethods[]
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(DeliveryMethods $delivery): self
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries[] = $delivery;
        }

        return $this;
    }

    public function removeDelivery(DeliveryMethods $delivery): self
    {
        if ($this->deliveries->contains($delivery)) {
            $this->deliveries->removeElement($delivery);
        }

        return $this;
    }
}
