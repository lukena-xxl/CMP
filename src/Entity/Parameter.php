<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParameterRepository")
 */
class Parameter
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
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="parameters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="parameter_id")
     */
    private $productParameters;

    public function __construct()
    {
        $this->productParameters = new ArrayCollection();
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
     * @return Collection|ProductParameter[]
     */
    public function getProductParameters(): Collection
    {
        return $this->productParameters;
    }

    public function addProductParameter(ProductParameter $productParameter): self
    {
        if (!$this->productParameters->contains($productParameter)) {
            $this->productParameters[] = $productParameter;
            $productParameter->setParameterId($this);
        }

        return $this;
    }

    public function removeProductParameter(ProductParameter $productParameter): self
    {
        if ($this->productParameters->contains($productParameter)) {
            $this->productParameters->removeElement($productParameter);
            // set the owning side to null (unless already changed)
            if ($productParameter->getParameterId() === $this) {
                $productParameter->setParameterId(null);
            }
        }

        return $this;
    }
}
