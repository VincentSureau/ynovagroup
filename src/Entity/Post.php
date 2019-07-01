<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"post"}},
 *     denormalizationContext={"groups"={"postWrite"}},
 *     collectionOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"post"}},
 *         },
 *         "post",
 *      },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"post"}}
 *         },
 *         "put"={
 *             "normalization_context"={"groups"={"postWrite"}},
 *         },
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN')", "access_control_message"="Désolé mais mais seuls les administrateurs peuvent supprimer un utilisateur"}
 *     },
 * )
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"post"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"post", "postWrite"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"post", "postWrite"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @Groups({"post", "postWrite"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"post", "postWrite"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=84, nullable=true)
     * @Gedmo\Slug(fields={"title"})
     * @Groups({"post"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"post", "postWrite"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->isActive = true;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
