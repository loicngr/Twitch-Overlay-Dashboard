<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    'user:read:collection',
                ],
            ],
        ),
        new Post(),
        new Patch(),
    ],
    normalizationContext: [
        'groups' => [
            'user:read:item',
        ],
    ],
    denormalizationContext: [
        'groups' => [
            'user:create:item',
            'user:update:item',
        ],
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'game:read:item',
        'stream:read:item',
    ])]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'user:update:item',
    ])]
    protected ?string $login = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'user:update:item',
        'game:read:item',
        'stream:read:item',
    ])]
    protected ?string $displayName = null;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
    )]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'user:update:item',
    ])]
    protected ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'user:update:item',
    ])]
    protected ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => Variables::DATE_SERVER,
    ])]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
    ])]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'user:update:item',
    ])]
    protected ?int $viewCount = null;

    #[ORM\Column(length: 300, nullable: true)]
    #[Groups([
        'user:read:collection',
        'user:read:item',
        'user:create:item',
        'user:update:item',
        'game:read:item',
        'stream:read:item',
    ])]
    protected ?string $profilePicture = null;

    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Stream::class,
        orphanRemoval: true,
    )]
    #[Groups([
        'user:read:item',
    ])]
    protected ?Collection $streams = null;

    public function __construct()
    {
        $this->streams = new ArrayCollection();
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

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(?int $viewCount): static
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getStreams(): Collection
    {
        return $this->streams ??= new ArrayCollection();
    }

    public function addStream(Stream $stream): self
    {
        if (!$this->getStreams()->contains($stream)) {
            $this->streams->add($stream);
            $stream->setUser($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): self
    {
        if ($this->getStreams()->removeElement($stream)) {
            if ($stream->getUser() === $this) {
                $stream->setUser(null);
            }
        }

        return $this;
    }
}
