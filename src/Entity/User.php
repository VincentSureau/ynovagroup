<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
Use App\Controller\Api\GetUserController;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet email.")
 * 
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user", "userWrite", "company", "receivedFile"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user", "userWrite"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=190, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"user", "userWrite", "company", "post", "file", "receivedFile"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Groups({"user", "userWrite", "company", "post", "file", "receivedFile"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=190, unique=true, nullable=true)
     * @Gedmo\Slug(fields={"firstname", "lastname"})
     * @Groups({"user", "userWrite"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user", "userWrite"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"userWrite"})
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Files", mappedBy="commercial")
     */
    private $managedFiles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Company", inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Groups({"user", "userWrite", "receivedFile"})
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="commercial")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $managedCompanies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author")
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Files", mappedBy="sentBy")
     */
    private $files;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Files", mappedBy="readBy")
     */
    private $readFiles;

    public function __construct()
    {
        $this->managedFiles = new ArrayCollection();
        $this->managedCompanies = new ArrayCollection();
        $this->isActive = true;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->posts = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->readFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_MEMBER';

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

    public function setPassword(string $password): self
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    /**
     * @return Collection|Files[]
     */
    public function getManagedFiles(): Collection
    {
        return $this->managedFiles;
    }

    public function addManagedFile(Files $managedFile): self
    {
        if (!$this->managedFiles->contains($managedFile)) {
            $this->managedFiles[] = $managedFile;
            $managedFile->setCommercial($this);
        }

        return $this;
    }

    public function removeManagedFile(Files $managedFile): self
    {
        if ($this->managedFiles->contains($managedFile)) {
            $this->managedFiles->removeElement($managedFile);
            // set the owning side to null (unless already changed)
            if ($managedFile->getCommercial() === $this) {
                $managedFile->setCommercial(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getManagedCompanies(): Collection
    {
        return $this->managedCompanies;
    }

    public function addManagedCompany(Company $managedCompany): self
    {
        if (!$this->managedCompanies->contains($managedCompany)) {
            $this->managedCompanies[] = $managedCompany;
            $managedCompany->setCommercial($this);
        }

        return $this;
    }

    public function removeManagedCompany(Company $managedCompany): self
    {
        if ($this->managedCompanies->contains($managedCompany)) {
            $this->managedCompanies->removeElement($managedCompany);
            // set the owning side to null (unless already changed)
            if ($managedCompany->getCommercial() === $this) {
                $managedCompany->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstname .' '. $this->lastname;
    }

    /**
     * @return Collection|Files[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setSentBy($this);
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getSentBy() === $this) {
                $file->setSentBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Files[]
     */
    public function getReadFiles(): Collection
    {
        return $this->readFiles;
    }

    public function addReadFile(Files $readFile): self
    {
        if (!$this->readFiles->contains($readFile)) {
            $this->readFiles[] = $readFile;
            $readFile->addReadBy($this);
        }

        return $this;
    }

    public function removeReadFile(Files $readFile): self
    {
        if ($this->readFiles->contains($readFile)) {
            $this->readFiles->removeElement($readFile);
            $readFile->removeReadBy($this);
        }

        return $this;
    }

}
