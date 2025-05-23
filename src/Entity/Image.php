<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
#[ORM\Table(name: 'images')]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private \DateTimeInterface $creation_date;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: Types::JSON)]
    private array $metadata = [];

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'excludedImages')]
    private Collection $excludedUsers;

    public function __construct()
    {
        $this->excludedUsers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getCreationDate(): \DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setMetadata(array $metadata): static
    {
        $this->metadata = $metadata;

        return $this;
    }

    public static function createFromData(string $fileName, string $title): self
    {
        $image = new self();
        $image
            ->setPath($fileName)
            ->setTitle($title)
            ->setMetadata([])
            ->setCreationDate(new \DateTime());

        return $image;
    }

    /**
     * @return Collection<int, User>
     */
    public function getExcludedUsers(): Collection
    {
        return $this->excludedUsers;
    }

    public function addExcludedUser(User $excludedUser): static
    {
        if (!$this->excludedUsers->contains($excludedUser)) {
            $this->excludedUsers->add($excludedUser);
        }

        return $this;
    }

    public function removeExcludedUser(User $excludedUser): static
    {
        $this->excludedUsers->removeElement($excludedUser);

        return $this;
    }

    public function isExcluded(): bool
    {
        return count($this->excludedUsers) > 0;

    }
}
