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
 * @ORM\Entity(repositoryClass="App\Repository\ParameterRepository")
 */
class Parameter implements Translatable
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="parameters")
     * @Groups({"read", "write"})
     */
    private $parameter_categories;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductParameter", mappedBy="parameter")
     */
    private $product;

    public function __construct()
    {
        $this->parameter_categories = new ArrayCollection();
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

    /**
     * @return Collection|ProductParameter[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(ProductParameter $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setParameter($this);
        }

        return $this;
    }

    public function removeProduct(ProductParameter $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getParameter() === $this) {
                $product->setParameter(null);
            }
        }

        return $this;
    }
}
