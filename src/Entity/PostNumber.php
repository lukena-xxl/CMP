<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostNumberRepository")
 */
class PostNumber
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="postNumbers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DeliveryMethods", inversedBy="postNumbers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delivery_method_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostNumberStatus", mappedBy="post_number_id")
     */
    private $postNumberStatuses;

    public function __construct()
    {
        $this->postNumberStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->order_id;
    }

    public function setOrderId(?Order $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getDeliveryMethodId(): ?DeliveryMethods
    {
        return $this->delivery_method_id;
    }

    public function setDeliveryMethodId(?DeliveryMethods $delivery_method_id): self
    {
        $this->delivery_method_id = $delivery_method_id;

        return $this;
    }

    /**
     * @return Collection|PostNumberStatus[]
     */
    public function getPostNumberStatuses(): Collection
    {
        return $this->postNumberStatuses;
    }

    public function addPostNumberStatus(PostNumberStatus $postNumberStatus): self
    {
        if (!$this->postNumberStatuses->contains($postNumberStatus)) {
            $this->postNumberStatuses[] = $postNumberStatus;
            $postNumberStatus->setPostNumberId($this);
        }

        return $this;
    }

    public function removePostNumberStatus(PostNumberStatus $postNumberStatus): self
    {
        if ($this->postNumberStatuses->contains($postNumberStatus)) {
            $this->postNumberStatuses->removeElement($postNumberStatus);
            // set the owning side to null (unless already changed)
            if ($postNumberStatus->getPostNumberId() === $this) {
                $postNumberStatus->setPostNumberId(null);
            }
        }

        return $this;
    }
}
