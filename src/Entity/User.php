<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"login"}, message="There is already an account with this login")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $middle_name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $second_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birth_date;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $registration_date;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    private $is_block = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="user")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Coefficient", mappedBy="user")
     */
    private $coefficients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Availability", mappedBy="user")
     */
    private $availabilities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCaption", mappedBy="user")
     */
    private $productCaptions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="admin")
     */
    private $admin_orders;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->coefficients = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
        $this->productCaptions = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->admin_orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }

    public function setMiddleName(?string $middle_name): self
    {
        $this->middle_name = $middle_name;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->second_name;
    }

    public function setSecondName(?string $second_name): self
    {
        $this->second_name = $second_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(?\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): self
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getIsBlock(): ?bool
    {
        return $this->is_block;
    }

    public function setIsBlock(bool $is_block): self
    {
        $this->is_block = $is_block;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Coefficient[]
     */
    public function getCoefficients(): Collection
    {
        return $this->coefficients;
    }

    public function addCoefficient(Coefficient $coefficient): self
    {
        if (!$this->coefficients->contains($coefficient)) {
            $this->coefficients[] = $coefficient;
            $coefficient->setUser($this);
        }

        return $this;
    }

    public function removeCoefficient(Coefficient $coefficient): self
    {
        if ($this->coefficients->contains($coefficient)) {
            $this->coefficients->removeElement($coefficient);
            // set the owning side to null (unless already changed)
            if ($coefficient->getUser() === $this) {
                $coefficient->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Availability[]
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(Availability $availability): self
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities[] = $availability;
            $availability->setUser($this);
        }

        return $this;
    }

    public function removeAvailability(Availability $availability): self
    {
        if ($this->availabilities->contains($availability)) {
            $this->availabilities->removeElement($availability);
            // set the owning side to null (unless already changed)
            if ($availability->getUser() === $this) {
                $availability->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductCaption[]
     */
    public function getProductCaptions(): Collection
    {
        return $this->productCaptions;
    }

    public function addProductCaption(ProductCaption $productCaption): self
    {
        if (!$this->productCaptions->contains($productCaption)) {
            $this->productCaptions[] = $productCaption;
            $productCaption->setUser($this);
        }

        return $this;
    }

    public function removeProductCaption(ProductCaption $productCaption): self
    {
        if ($this->productCaptions->contains($productCaption)) {
            $this->productCaptions->removeElement($productCaption);
            // set the owning side to null (unless already changed)
            if ($productCaption->getUser() === $this) {
                $productCaption->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Orders[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Orders[]
     */
    public function getAdminOrders(): Collection
    {
        return $this->admin_orders;
    }

    public function addAdminOrder(Orders $adminOrder): self
    {
        if (!$this->admin_orders->contains($adminOrder)) {
            $this->admin_orders[] = $adminOrder;
            $adminOrder->setAdmin($this);
        }

        return $this;
    }

    public function removeAdminOrder(Orders $adminOrder): self
    {
        if ($this->admin_orders->contains($adminOrder)) {
            $this->admin_orders->removeElement($adminOrder);
            // set the owning side to null (unless already changed)
            if ($adminOrder->getAdmin() === $this) {
                $adminOrder->setAdmin(null);
            }
        }

        return $this;
    }
}
