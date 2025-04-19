<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\OneToOne(mappedBy: 'User', cascade: ['persist', 'remove'])]
    private ?ExcludedImage $excludedImages = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getExcludedImages(): ?ExcludedImage
    {
        return $this->excludedImages;
    }

    //    public function setExcludedImages(ExcludedImage $excludedImages): static
    //    {
    //        // set the owning side of the relation if necessary
    //        if ($excludedImages->getUser() !== $this) {
    //            $excludedImages->setUser($this);
    //        }
    //
    //        $this->excludedImages = $excludedImages;
    //
    //        return $this;
    //    }
}
