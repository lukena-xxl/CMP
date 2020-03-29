<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(type="string", length=200)
     */
    private $slug;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @var datetime $creation_date
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @var datetime $update_date
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $update_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="products")
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Availability", inversedBy="products")
     */
    private $availability;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductCaption", inversedBy="products")
     */
    private $captions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFilter", mappedBy="product")
     */
    private $filters;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductItem", mappedBy="product", cascade={"persist", "remove"})
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DeliveryMethod", inversedBy="products")
     */
    private $delivery;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PaymentMethod", inversedBy="products")
     */
    private $payment;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function __construct()
    {
        $this->captions = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->filters = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->delivery = new ArrayCollection();
        $this->payment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

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

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAvailability(): ?Availability
    {
        return $this->availability;
    }

    public function setAvailability(?Availability $availability): self
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * @return Collection|ProductCaption[]
     */
    public function getCaptions(): Collection
    {
        return $this->captions;
    }

    public function addCaption(ProductCaption $caption): self
    {
        if (!$this->captions->contains($caption)) {
            $this->captions[] = $caption;
        }

        return $this;
    }

    public function removeCaption(ProductCaption $caption): self
    {
        if ($this->captions->contains($caption)) {
            $this->captions->removeElement($caption);
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
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(ProductImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
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
            $filter->setProduct($this);
        }

        return $this;
    }

    public function removeFilter(ProductFilter $filter): self
    {
        if ($this->filters->contains($filter)) {
            $this->filters->removeElement($filter);
            // set the owning side to null (unless already changed)
            if ($filter->getProduct() === $this) {
                $filter->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ProductItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setProduct($this);
        }

        return $this;
    }

    public function removeItem(ProductItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getProduct() === $this) {
                $item->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DeliveryMethod[]
     */
    public function getDelivery(): Collection
    {
        return $this->delivery;
    }

    public function addDelivery(DeliveryMethod $delivery): self
    {
        if (!$this->delivery->contains($delivery)) {
            $this->delivery[] = $delivery;
        }

        return $this;
    }

    public function removeDelivery(DeliveryMethod $delivery): self
    {
        if ($this->delivery->contains($delivery)) {
            $this->delivery->removeElement($delivery);
        }

        return $this;
    }

    /**
     * @return Collection|PaymentMethod[]
     */
    public function getPayment(): Collection
    {
        return $this->payment;
    }

    public function addPayment(PaymentMethod $payment): self
    {
        if (!$this->payment->contains($payment)) {
            $this->payment[] = $payment;
        }

        return $this;
    }

    public function removePayment(PaymentMethod $payment): self
    {
        if ($this->payment->contains($payment)) {
            $this->payment->removeElement($payment);
        }

        return $this;
    }
}
