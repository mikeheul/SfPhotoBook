<?php

namespace App\Entity;

use App\Repository\PackageRepository;
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
}
