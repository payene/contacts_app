<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\ContactOwnerProcessor;

use DateTimeImmutable;

#[ApiResource(
    operations: [
        new Post(
            processor: ContactOwnerProcessor::class
        )
    ]
)]

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $firstName = null;

    #[ORM\Column(length: 30)]
    private ?string $lastName = null;

    #[ORM\Column(length: 80)]
    private ?string $principalPhoneNumber = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $secondPhoneNumber = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'contactsList', targetEntity: User::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]

    #[ORM\JoinColumn(nullable: false)]
    private ?user $owner = null;

    #[ORM\Column]
    private ?bool $delete = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(){
            $this->createdAt = new \DateTimeImmutable();
            $this->delete = false;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPrincipalPhoneNumber(): ?string
    {
        return $this->principalPhoneNumber;
    }

    public function setPrincipalPhoneNumber(string $principalPhoneNumber): static
    {
        $this->principalPhoneNumber = $principalPhoneNumber;

        return $this;
    }

    public function getSecondPhoneNumber(): ?string
    {
        return $this->secondPhoneNumber;
    }

    public function setSecondPhoneNumber(?string $secondPhoneNumber): static
    {
        $this->secondPhoneNumber = $secondPhoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?user
    {
        return $this->owner;
    }

    public function setOwner(?user $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): static
    {
        $this->delete = $delete;

        return $this;
    }
}
