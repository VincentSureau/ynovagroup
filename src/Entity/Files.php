<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


// /vianney que souhaites-tu serializer ici? peux-tu voir pour l'upload des fichiers afin d'avoir quelques données et travailler dessus? Comment sécurises-tu les accès à l'API? je veux bien un exemple de la façon dont tu le fais, ainsi que les données à sécuriser

// je veux tout sérialiser sauf les updated at
// pour le controller, tu peux prendre exemple sur le getUserController
// il faut refaire un controller de ce type, comme ça tu peux faire toutes les vérification
// dedans, du style $repositiry->findByCommercial($this->getUser())
// car de base api platform ne permet pas de faire ça

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilesRepository")
 * 
 *  @ApiResource(
 *     normalizationContext={"groups"={"file"}},
 *     denormalizationContext={"groups"={"fileWrite"}},
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"file"}},
 *         },
 *         "post",
 *      },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"file"}}
 *         },
 *         "put"={
 *             "normalization_context"={"groups"={"fileWrite"}},
 *             "access_control"="is_granted('ROLE_USER') and user.getFiles().contains(object) or is_granted('ROLE_BUSINESS')"},
 *         "delete"={"access_control"="is_granted('ROLE_BUSINESS')"}
 *     },
 * )
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
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="files")
     * @Groups({"file", "fileWrite"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addFile($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeFile($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->name;
    }
}
