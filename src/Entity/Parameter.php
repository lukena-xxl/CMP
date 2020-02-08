<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParameterRepository")
 */
class Parameter implements Translatable
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="parameters")
     */
    private $parameter_categories;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    public function __construct()
    {
        $this->parameter_categories = new ArrayCollection();
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
    public function getParameterCategories(): Collection
    {
        return $this->parameter_categories;
    }

    public function addParameterCategory(Category $parameterCategory): self
    {
        if (!$this->parameter_categories->contains($parameterCategory)) {
            $this->parameter_categories[] = $parameterCategory;
        }

        return $this;
    }

    public function removeParameterCategory(Category $parameterCategory): self
    {
        if ($this->parameter_categories->contains($parameterCategory)) {
            $this->parameter_categories->removeElement($parameterCategory);
        }

        return $this;
    }
}
