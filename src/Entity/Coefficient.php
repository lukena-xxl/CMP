<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CoefficientRepository")
 */
class Coefficient
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
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $ratio;

    /**
     * @var datetime $update_date
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductItem", mappedBy="coefficient")
     */
    private $productItems;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="coefficients")
     */
    private $user;

    public function __construct()
    {
        $this->productItems = new ArrayCollection();
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

    public function getRatio(): ?float
    {
        return $this->ratio;
    }

    public function setRatio(float $ratio): self
    {
        $this->ratio = $ratio;

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

    /**
     * @return Collection|ProductItem[]
     */
    public function getProductItems(): Collection
    {
        return $this->productItems;
    }

    public function addProductItem(ProductItem $productItem): self
    {
        if (!$this->productItems->contains($productItem)) {
            $this->productItems[] = $productItem;
            $productItem->setCoefficient($this);
        }

        return $this;
    }

    public function removeProductItem(ProductItem $productItem): self
    {
        if ($this->productItems->contains($productItem)) {
            $this->productItems->removeElement($productItem);
            // set the owning side to null (unless already changed)
            if ($productItem->getCoefficient() === $this) {
                $productItem->setCoefficient(null);
            }
        }

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
}
