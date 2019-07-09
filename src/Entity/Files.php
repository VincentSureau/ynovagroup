<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilesRepository")
 * 
 */
class Files
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"file"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190)
     * @Groups({"user", "file", "fileWrite"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=190)
     * @Groups({"user","file", "fileWrite"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=190)
     * @Groups({"file", "fileWrite"})
     */
    private $path;

    /**
     * @ORM\Column(type="text")
     * @Groups({"file", "fileWrite"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     * @Gedmo\Slug(fields={"name"})
     * @Groups({"file"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"file", "fileWrite"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"file"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"file", "fileWrite"})
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"file", "fileWrite"})
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="managedFiles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"file",  "fileWrite"})
     */
    private $commercial;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="files")
     */
    private $sentBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Company", inversedBy="files")
     */
    private $pharmacies;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->pharmacies = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
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

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

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

    public function __toString() {
        return $this->name;
    }

    public function getSentBy(): ?User
    {
        return $this->sentBy;
    }

    public function setSentBy(?User $sentBy): self
    {
        $this->sentBy = $sentBy;

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getPharmacies(): Collection
    {
        return $this->pharmacies;
    }

    public function addPharmacy(Company $pharmacy): self
    {
        if (!$this->pharmacies->contains($pharmacy)) {
            $this->pharmacies[] = $pharmacy;
        }

        return $this;
    }

    public function removePharmacy(Company $pharmacy): self
    {
        if ($this->pharmacies->contains($pharmacy)) {
            $this->pharmacies->removeElement($pharmacy);
        }

        return $this;
    }
}
