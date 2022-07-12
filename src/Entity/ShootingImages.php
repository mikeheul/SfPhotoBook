<?php

namespace App\Entity;

use App\Repository\ShootingImagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShootingImagesRepository::class)
 */
class ShootingImages
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
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Shooting::class, inversedBy="shootingImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shooting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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
}
