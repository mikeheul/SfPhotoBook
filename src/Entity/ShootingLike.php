<?php

namespace App\Entity;

use App\Repository\ShootingLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShootingLikeRepository::class)
 */
class ShootingLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Shooting::class, inversedBy="shootingLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shooting;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shootingLikes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
