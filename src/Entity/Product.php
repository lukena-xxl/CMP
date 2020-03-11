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
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount_percentage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $discount_start_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $discount_end_date;

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
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFilter", mappedBy="product")
     */
    private $filters;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="product")
     */
    private $parameters;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price_purchase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="products_purchase")
     */
    private $currency_purchase;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Coefficient", inversedBy="products")
     */
    private $coefficient;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function __construct()
    {
        $this->captions = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->filters = new ArrayCollection();
        $this->parameters = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    public function getDiscountStartDate(): ?\DateTimeInterface
    {
        return $this->discount_start_date;
    }

    public function setDiscountStartDate(?\DateTimeInterface $discount_start_date): self
    {
        $this->discount_start_date = $discount_start_date;

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
            // set the owning side to null (unless already changed)
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
            $parameter->setProduct($this);
        }

        return $this;
    }

    public function removeParameter(ProductParameter $parameter): self
    {
        if ($this->parameters->contains($parameter)) {
            $this->parameters->removeElement($parameter);
            // set the owning side to null (unless already changed)
            if ($parameter->getProduct() === $this) {
                $parameter->setProduct(null);
            }
        }

        return $this;
    }

    public function getPricePurchase(): ?float
    {
        return $this->price_purchase;
    }

    public function setPricePurchase(?float $price_purchase): self
    {
        $this->price_purchase = $price_purchase;

        return $this;
    }

    public function getCurrencyPurchase(): ?Currency
    {
        return $this->currency_purchase;
    }

    public function setCurrencyPurchase(?Currency $currency_purchase): self
    {
        $this->currency_purchase = $currency_purchase;

        return $this;
    }

    public function getCoefficient(): ?Coefficient
    {
        return $this->coefficient;
    }

    public function setCoefficient(?Coefficient $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }
}
