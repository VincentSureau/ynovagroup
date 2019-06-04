<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
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
     * @ORM\OneToMany(targetEntity="App\Entity\Files", mappedBy="commercial")
     */
    private $managedFiles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Files", inversedBy="users")
     */
    private $files;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Company", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="commercial")
     */
    private $managedCompanies;

    public function __construct()
    {
        $this->managedFiles = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->managedCompanies = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
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
}
