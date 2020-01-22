<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductParameterRepository")
 */
class ProductParameter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_visible;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="parameters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Parameter", inversedBy="productParameters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parameter_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
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

    public function getProductId(): ?Product
    {
        return $this->product_id;
    }

    public function setProductId(?Product $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getParameterId(): ?Parameter
    {
        return $this->parameter_id;
    }

    public function setParameterId(?Parameter $parameter_id): self
    {
        $this->parameter_id = $parameter_id;

        return $this;
    }
}
