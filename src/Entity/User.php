<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Image>
     */
    #[ORM\ManyToMany(targetEntity: Image::class, mappedBy: 'excludedUsers')]
    private Collection $excludedImages;

    public function __construct()
    {
        $this->excludedImages = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Image>
     */
    public function getExcludedImages(): Collection
    {
        return $this->excludedImages;
    }

    public function addExcludedImage(Image $excludedImage): static
    {
        if (!$this->excludedImages->contains($excludedImage)) {
            $this->excludedImages->add($excludedImage);
            $excludedImage->addExcludedUser($this);
        }

        return $this;
    }

    public function removeExcludedImage(Image $excludedImage): static
    {
        if ($this->excludedImages->removeElement($excludedImage)) {
            $excludedImage->removeExcludedUser($this);
        }

        return $this;
    }
}
