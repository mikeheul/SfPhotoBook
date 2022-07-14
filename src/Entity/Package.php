<?php

namespace App\Entity;

use App\Repository\PackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PackageRepository::class)
 */
class Package
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hours;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPhotos;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Shooting::class, inversedBy="packages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shooting;

    /**
     * @ORM\OneToMany(targetEntity=ShootingBook::class, mappedBy="package")
     */
    private $shootingBooks;

    public function __construct()
    {
        $this->shootingBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getNbPhotos(): ?int
    {
        return $this->nbPhotos;
    }

    public function setNbPhotos(int $nbPhotos): self
    {
        $this->nbPhotos = $nbPhotos;

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

    public function getShooting(): ?Shooting
    {
        return $this->shooting;
    }

    public function setShooting(?Shooting $shooting): self
    {
        $this->shooting = $shooting;

        return $this;
    }

    public function __toString()
    {
        return $this->hours." h. - ".$this->nbPhotos." retouched photos - $ ".$this->price;
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
            $shootingBook->setPackage($this);
        }

        return $this;
    }

    public function removeShootingBook(ShootingBook $shootingBook): self
    {
        if ($this->shootingBooks->removeElement($shootingBook)) {
            // set the owning side to null (unless already changed)
            if ($shootingBook->getPackage() === $this) {
                $shootingBook->setPackage(null);
            }
        }

        return $this;
    }
}
