<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Secured resource.
 *
 * @ApiResource(
 *     attributes={
 *         "security"="is_granted('ROLE_USER')",
 *         "normalization_context"={"groups"={"read"}},
 *         "denormalization_context"={"groups"={"write"}}},
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"security"="is_granted('ROLE_ADMIN')"},
 *         "patch"={"security"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ApiFilter(OrderFilter::class, properties={"id"})
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=50)
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=10)
     * @Groups({"read", "write"})
     */
    private $short;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"read", "write"})
     */
    private $abbr;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"read", "write"})
     */
    private $symbol;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $display;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="currency")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="currency_purchase")
     */
    private $products_purchase;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->products_purchase = new ArrayCollection();
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

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }

    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    public function setAbbr(string $abbr): self
    {
        $this->abbr = $abbr;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(string $display): self
    {
        $this->display = $display;

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
            $product->setCurrency($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCurrency() === $this) {
                $product->setCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProductsPurchase(): Collection
    {
        return $this->products_purchase;
    }

    public function addProductsPurchase(Product $productsPurchase): self
    {
        if (!$this->products_purchase->contains($productsPurchase)) {
            $this->products_purchase[] = $productsPurchase;
            $productsPurchase->setCurrencyPurchase($this);
        }

        return $this;
    }

    public function removeProductsPurchase(Product $productsPurchase): self
    {
        if ($this->products_purchase->contains($productsPurchase)) {
            $this->products_purchase->removeElement($productsPurchase);
            // set the owning side to null (unless already changed)
            if ($productsPurchase->getCurrencyPurchase() === $this) {
                $productsPurchase->setCurrencyPurchase(null);
            }
        }

        return $this;
    }
}
