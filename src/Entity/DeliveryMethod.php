<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
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
 * @ApiFilter(OrderFilter::class, properties={"id", "position"})
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryMethodRepository")
 */
class DeliveryMethod implements Translatable
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
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $short_description;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read", "write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read", "write"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Groups({"read", "write"})
     */
    private $key_1;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Groups({"read", "write"})
     */
    private $key_2;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Groups({"read", "write"})
     */
    private $key_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $note_key_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $note_key_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read", "write"})
     */
    private $note_key_3;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @Groups({"read", "write"})
     */
    private $is_visible = false;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $position;

    /**
     * @Gedmo\Locale
     */
    private $locale = 'ru';

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

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

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

    public function getKey1(): ?string
    {
        return $this->key_1;
    }

    public function setKey1(?string $key_1): self
    {
        $this->key_1 = $key_1;

        return $this;
    }

    public function getKey2(): ?string
    {
        return $this->key_2;
    }

    public function setKey2(?string $key_2): self
    {
        $this->key_2 = $key_2;

        return $this;
    }

    public function getKey3(): ?string
    {
        return $this->key_3;
    }

    public function setKey3(?string $key_3): self
    {
        $this->key_3 = $key_3;

        return $this;
    }

    public function getNoteKey1(): ?string
    {
        return $this->note_key_1;
    }

    public function setNoteKey1(?string $note_key_1): self
    {
        $this->note_key_1 = $note_key_1;

        return $this;
    }

    public function getNoteKey2(): ?string
    {
        return $this->note_key_2;
    }

    public function setNoteKey2(?string $note_key_2): self
    {
        $this->note_key_2 = $note_key_2;

        return $this;
    }

    public function getNoteKey3(): ?string
    {
        return $this->note_key_3;
    }

    public function setNoteKey3(?string $note_key_3): self
    {
        $this->note_key_3 = $note_key_3;

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
