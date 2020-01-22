<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilterRepository")
 */
class Filter
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
     * @ORM\Column(type="boolean")
     */
    private $is_visible;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="filters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductFilter", mappedBy="filter_id")
     */
    private $productFilters;

    public function __construct()
    {
        $this->productFilters = new ArrayCollection();
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

    public function getCategoryId(): ?Category
    {
        return $this->category_id;
    }

    public function setCategoryId(?Category $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * @return Collection|ProductFilter[]
     */
    public function getProductFilters(): Collection
    {
        return $this->productFilters;
    }

    public function addProductFilter(ProductFilter $productFilter): self
    {
        if (!$this->productFilters->contains($productFilter)) {
            $this->productFilters[] = $productFilter;
            $productFilter->setFilterId($this);
        }

        return $this;
    }

    public function removeProductFilter(ProductFilter $productFilter): self
    {
        if ($this->productFilters->contains($productFilter)) {
            $this->productFilters->removeElement($productFilter);
            // set the owning side to null (unless already changed)
            if ($productFilter->getFilterId() === $this) {
                $productFilter->setFilterId(null);
            }
        }

        return $this;
    }
}
