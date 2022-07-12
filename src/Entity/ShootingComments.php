<?php

namespace App\Entity;

use App\Repository\ShootingCommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShootingCommentsRepository::class)
 */
class ShootingComments
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="shootingComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userComment;

    /**
     * @ORM\ManyToOne(targetEntity=Shooting::class, inversedBy="shootingComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shootingComment;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserComment(): ?User
    {
        return $this->userComment;
    }

    public function setUserComment(?User $userComment): self
    {
        $this->userComment = $userComment;

        return $this;
    }

    public function getShootingComment(): ?Shooting
    {
        return $this->shootingComment;
    }

    public function setShootingComment(?Shooting $shootingComment): self
    {
        $this->shootingComment = $shootingComment;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
}
