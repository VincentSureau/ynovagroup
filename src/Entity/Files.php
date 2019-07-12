<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilesRepository")
 * @Vich\Uploadable
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
     * @Groups({"file", "fileWrite"})
     */
    private $document;

    /**
     * @Vich\UploadableField(mapping="files", fileNameProperty="document")
     * @var File
     */
    private $documentFile;

    /**
     * @ORM\Column(type="text", nullable=true)
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
     * @Groups({"file",  "fileWrite"})
     */
    private $sentBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Company", inversedBy="files")
     * @Groups({"file",  "fileWrite"})
     */
    private $pharmacies;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="readFiles")
     * @Groups({"file",  "fileWrite"})
     */
    private $readBy;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->pharmacies = new ArrayCollection();
        $this->readBy = new ArrayCollection();
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

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

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

    public function setDeletedAt(\DateTimeInterface $deletedAt = null): self
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

    public function setDocumentFile(File $image = null)
    {
        $this->documentFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    /**
     * @return Collection|User[]
     */
    public function getReadBy(): Collection
    {
        return $this->readBy;
    }

    public function addReadBy(User $readBy): self
    {
        if (!$this->readBy->contains($readBy)) {
            $this->readBy[] = $readBy;
        }

        return $this;
    }

    public function removeReadBy(User $readBy): self
    {
        if ($this->readBy->contains($readBy)) {
            $this->readBy->removeElement($readBy);
        }

        return $this;
    }
}
