<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"company"}},
 *     denormalizationContext={"groups"={"companyWrite"}},
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"company"}},
 *         },
 *         "post",
 *      },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"company"}}
 *         },
 *         "put"={
 *             "normalization_context"={"groups"={"companyWrite"}},
 *             "access_control"="is_granted('ROLE_USER') and object == user.getCompany() or is_granted('ROLE_ADMIN')", "access_control_message"="Désolé, vous ne pouvez pas modifier une autre pharmacie"
 *         },
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN')", "access_control_message"="Désolé mais mais seuls les administrateurs peuvent supprimer une pharmacie"}
 *     },
 * )
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"company"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     * @Groups({"user","company"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"company"})
     */
    private $firstAdressField;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"company"})
     */
    private $secondAdressField;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"company"})
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"company"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"company"})
     */
    private $country;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"company"})
     */
    private $description;

    /**
     * @Assert\File(
     *      maxSize = "1024k", 
     *      mimeTypes={"image/jpeg", "image/png" },
     *      mimeTypesMessage = "Merci de fournir un format valide : png, jpeg"
     * )
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"company"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"company"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="company", cascade={"persist", "remove"})
     * @Groups({"company"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="managedCompanies")
     * @Groups({"company"})
     */
    private $commercial;

    public function __construct()
    {
        $this->isActive = true;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getFirstAdressField(): ?string
    {
        return $this->firstAdressField;
    }

    public function setFirstAdressField(?string $firstAdressField): self
    {
        $this->firstAdressField = $firstAdressField;

        return $this;
    }

    public function getsecondAdressField(): ?string
    {
        return $this->secondAdressField;
    }

    public function setsecondAdressField(?string $secondAdressField): self
    {
        $this->secondAdressField = $secondAdressField;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        // set the owning side of the relation if necessary
        if ($this !== $user->getCompany()) {
            $user->setCompany($this);
        }

        return $this;
    }

    public function getCommercial(): ?User
    {
        return $this->commercial;
    }

    public function setCommercial(?User $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }
}
