<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ShootingRepository::class)
 */
class Shooting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shootings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=ShootingComments::class, mappedBy="shootingComment", orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" : "DESC"})
     */
    private $shootingComments;

    /**
     * @ORM\OneToMany(targetEntity=ShootingImages::class, mappedBy="shooting", orphanRemoval=true, cascade={"persist"})
     * @OrderBy({"id" : "DESC"})
     */
    private $shootingImages;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ShootingBook::class, mappedBy="shooting", orphanRemoval=true)
     */
    private $shootingBooks;

    /**
     * @ORM\OneToMany(targetEntity=ShootingLike::class, mappedBy="shooting", orphanRemoval=true)
     */
    private $shootingLikes;

    /**
     * @ORM\OneToMany(targetEntity=Package::class, mappedBy="shooting", orphanRemoval=true)
     */
    private $packages;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $localisation;

    public function __construct()
    {
        $this->shootingComments = new ArrayCollection();
        $this->shootingImages = new ArrayCollection();
        $this->shootingBooks = new ArrayCollection();
        $this->shootingLikes = new ArrayCollection();
        $this->packages = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
            $shootingComment->setShootingComment($this);
        }

        return $this;
    }

    public function removeShootingComment(ShootingComments $shootingComment): self
    {
        if ($this->shootingComments->removeElement($shootingComment)) {
            // set the owning side to null (unless already changed)
            if ($shootingComment->getShootingComment() === $this) {
                $shootingComment->setShootingComment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShootingImages>
     */
    public function getShootingImages(): Collection
    {
        return $this->shootingImages;
    }

    public function addShootingImage(ShootingImages $shootingImage): self
    {
        if (!$this->shootingImages->contains($shootingImage)) {
            $this->shootingImages[] = $shootingImage;
            $shootingImage->setShooting($this);
        }

        return $this;
    }

    public function removeShootingImage(ShootingImages $shootingImage): self
    {
        if ($this->shootingImages->removeElement($shootingImage)) {
            // set the owning side to null (unless already changed)
            if ($shootingImage->getShooting() === $this) {
                $shootingImage->setShooting(null);
            }
        }

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
            $shootingBook->setShooting($this);
        }

        return $this;
    }

    public function removeShootingBook(ShootingBook $shootingBook): self
    {
        if ($this->shootingBooks->removeElement($shootingBook)) {
            // set the owning side to null (unless already changed)
            if ($shootingBook->getShooting() === $this) {
                $shootingBook->setShooting(null);
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
            $shootingLike->setShooting($this);
        }

        return $this;
    }

    public function removeShootingLike(ShootingLike $shootingLike): self
    {
        if ($this->shootingLikes->removeElement($shootingLike)) {
            // set the owning side to null (unless already changed)
            if ($shootingLike->getShooting() === $this) {
                $shootingLike->setShooting(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Package>
     */
    public function getPackages(): Collection
    {
        return $this->packages;
    }

    public function addPackage(Package $package): self
    {
        if (!$this->packages->contains($package)) {
            $this->packages[] = $package;
            $package->setShooting($this);
        }

        return $this;
    }

    public function removePackage(Package $package): self
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getShooting() === $this) {
                $package->setShooting(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
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

    public function isLikedByUser(User $user): ?bool
    {
        foreach($this->shootingLikes as $like) {
            if($like->getUser() === $user) return true; 
        }
        return false;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getAverage() {
        $this->average = $this->id / 5;
        return $this->average;
    }
}
