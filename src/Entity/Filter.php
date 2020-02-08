<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilterRepository")
 */
class Filter implements Translatable
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
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="filters")
     */
    private $filter_categories;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    public function __construct()
    {
        $this->filter_categories = new ArrayCollection();
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
}
