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
    private $categories;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FilterElement", mappedBy="filter", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $elements;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $position;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->elements = new ArrayCollection();
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
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|FilterElement[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(FilterElement $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
            $element->setFilter($this);
        }

        return $this;
    }

    public function removeElement(FilterElement $element): self
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
            // set the owning side to null (unless already changed)
            if ($element->getFilter() === $this) {
                $element->setFilter(null);
            }
        }

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
}
