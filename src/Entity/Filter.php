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
 * @ORM\Entity(repositoryClass="App\Repository\FilterRepository")
 */
class Filter implements Translatable
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
     * @ORM\Column(type="string", length=100)
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @Groups({"read", "write"})
     */
    private $is_visible = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="filters")
     * @Groups({"read", "write"})
     */
    private $filter_categories;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFilter", mappedBy="filter")
     */
    private $product;

    public function __construct()
    {
        $this->filter_categories = new ArrayCollection();
        $this->product = new ArrayCollection();
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

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getFilterCategories(): Collection
    {
        return $this->filter_categories;
    }

    public function addFilterCategory(Category $filterCategory): self
    {
        if (!$this->filter_categories->contains($filterCategory)) {
            $this->filter_categories[] = $filterCategory;
        }

        return $this;
    }

    public function removeFilterCategory(Category $filterCategory): self
    {
        if ($this->filter_categories->contains($filterCategory)) {
            $this->filter_categories->removeElement($filterCategory);
        }

        return $this;
    }

    /**
     * @return Collection|ProductFilter[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(ProductFilter $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setFilter($this);
        }

        return $this;
    }

    public function removeProduct(ProductFilter $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getFilter() === $this) {
                $product->setFilter(null);
            }
        }

        return $this;
    }
}
