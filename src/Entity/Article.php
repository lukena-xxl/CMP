<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_visible = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publish;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\ArticleCategory", inversedBy="articles")
     */
    private $article_category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ArticleTag", inversedBy="articles")
     */
    private $article_tags;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

    public function __construct()
    {
        $this->article_tags = new ArrayCollection();
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getPublish(): ?\DateTimeInterface
    {
        return $this->publish;
    }

    public function setPublish(?\DateTimeInterface $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getArticleCategory(): ?ArticleCategory
    {
        return $this->article_category;
    }

    public function setArticleCategory(?ArticleCategory $article_category): self
    {
        $this->article_category = $article_category;

        return $this;
    }

    /**
     * @return Collection|ArticleTag[]
     */
    public function getArticleTags(): Collection
    {
        return $this->article_tags;
    }

    public function addArticleTag(ArticleTag $articleTag): self
    {
        if (!$this->article_tags->contains($articleTag)) {
            $this->article_tags[] = $articleTag;
        }

        return $this;
    }

    public function removeArticleTag(ArticleTag $articleTag): self
    {
        if ($this->article_tags->contains($articleTag)) {
            $this->article_tags->removeElement($articleTag);
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
