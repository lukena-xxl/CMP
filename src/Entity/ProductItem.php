<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductItemRepository")
 */
class ProductItem implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $name;

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
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $img;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_checked = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Coefficient", inversedBy="productItems")
     */
    private $coefficient;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="items")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":1})
     */
    private $displayed_quantity;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default":1})
     */
    private $min_order_quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_order_quantity;

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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

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

    public function getIsChecked(): ?bool
    {
        return $this->is_checked;
    }

    public function setIsChecked(bool $is_checked): self
    {
        $this->is_checked = $is_checked;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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

    public function getDisplayedQuantity(): ?int
    {
        return $this->displayed_quantity;
    }

    public function setDisplayedQuantity(int $displayed_quantity): self
    {
        $this->displayed_quantity = $displayed_quantity;

        return $this;
    }

    public function getMinOrderQuantity(): ?int
    {
        return $this->min_order_quantity;
    }

    public function setMinOrderQuantity(int $min_order_quantity): self
    {
        $this->min_order_quantity = $min_order_quantity;

        return $this;
    }

    public function getMaxOrderQuantity(): ?int
    {
        return $this->max_order_quantity;
    }

    public function setMaxOrderQuantity(?int $max_order_quantity): self
    {
        $this->max_order_quantity = $max_order_quantity;

        return $this;
    }
}
