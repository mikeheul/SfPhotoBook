<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
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
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registerDate;

    /**
     * @ORM\OneToMany(targetEntity=Shooting::class, mappedBy="owner", orphanRemoval=true)
     */
    private $shootings;

    /**
     * @ORM\OneToMany(targetEntity=ShootingComments::class, mappedBy="userComment", orphanRemoval=true)
     */
    private $shootingComments;

    /**
     * @ORM\OneToMany(targetEntity=ShootingBook::class, mappedBy="bookUser", orphanRemoval=true)
     */
    private $shootingBooks;

    /**
     * @ORM\OneToMany(targetEntity=ShootingLike::class, mappedBy="user", orphanRemoval=true)
     */
    private $shootingLikes;

    public function __construct()
    {
        $this->shootings = new ArrayCollection();
        $this->shootingComments = new ArrayCollection();
        $this->shootingBooks = new ArrayCollection();
        $this->shootingLikes = new ArrayCollection();
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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

    public function addRole($role) {
        $this->roles[] = $role;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function isNew() {
        $diff = date_diff($this->registerDate, new \DateTime());
        $nbDays = (int) $diff->format('%a');
        return $nbDays <= 30;
    }

    /**
     * @return Collection<int, Shooting>
     */
    public function getShootings(): Collection
    {
        return $this->shootings;
    }

    public function addShooting(Shooting $shooting): self
    {
        if (!$this->shootings->contains($shooting)) {
            $this->shootings[] = $shooting;
            $shooting->setOwner($this);
        }

        return $this;
    }

    public function removeShooting(Shooting $shooting): self
    {
        if ($this->shootings->removeElement($shooting)) {
            // set the owning side to null (unless already changed)
            if ($shooting->getOwner() === $this) {
                $shooting->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShootingComments>
     */
    public function getShootingComments(): Collection
    {
        return $this->shootingComments;
    }

    public function addShootingComment(ShootingComments $shootingComment): self
    {
        if (!$this->shootingComments->contains($shootingComment)) {
            $this->shootingComments[] = $shootingComment;
            $shootingComment->setUserComment($this);
        }

        return $this;
    }

    public function removeShootingComment(ShootingComments $shootingComment): self
    {
        if ($this->shootingComments->removeElement($shootingComment)) {
            // set the owning side to null (unless already changed)
            if ($shootingComment->getUserComment() === $this) {
                $shootingComment->setUserComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShootingBook>
     */
    public function getShootingBooks(): Collection
    {
        return $this->shootingBooks;
    }

    public function addShootingBook(ShootingBook $shootingBook): self
    {
        if (!$this->shootingBooks->contains($shootingBook)) {
            $this->shootingBooks[] = $shootingBook;
            $shootingBook->setBookUser($this);
        }

        return $this;
    }

    public function removeShootingBook(ShootingBook $shootingBook): self
    {
        if ($this->shootingBooks->removeElement($shootingBook)) {
            // set the owning side to null (unless already changed)
            if ($shootingBook->getBookUser() === $this) {
                $shootingBook->setBookUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShootingLike>
     */
    public function getShootingLikes(): Collection
    {
        return $this->shootingLikes;
    }

    public function addShootingLike(ShootingLike $shootingLike): self
    {
        if (!$this->shootingLikes->contains($shootingLike)) {
            $this->shootingLikes[] = $shootingLike;
            $shootingLike->setUser($this);
        }

        return $this;
    }

    public function removeShootingLike(ShootingLike $shootingLike): self
    {
        if ($this->shootingLikes->removeElement($shootingLike)) {
            // set the owning side to null (unless already changed)
            if ($shootingLike->getUser() === $this) {
                $shootingLike->setUser(null);
            }
        }

        return $this;
    }
}
