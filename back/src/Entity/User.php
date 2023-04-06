<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\Utils\Constants\Groups;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups as ApiGroups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    Groups::GROUP_USER_READ_COLLECTION,
                ],
            ],
        ),
        new Post(
            validationContext: [
                'groups' => [
                    'Default',
                    Groups::GROUP_USER_CREATE_ITEM,
                ],
            ],
        ),
        new Patch(),
    ],
    normalizationContext: [
        'groups' => [
            Groups::GROUP_USER_READ_ITEM,
        ],
    ],
    denormalizationContext: [
        'groups' => [
            Groups::GROUP_USER_CREATE_ITEM,
            Groups::GROUP_USER_UPDATE_ITEM,
        ],
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\Column]
    #[Assert\NotBlank(groups: [
        Groups::GROUP_USER_CREATE_ITEM,
    ])]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
    ])]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_USER_UPDATE_ITEM,
    ])]
    protected ?string $login = null;

    #[ORM\Column(length: 255)]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_USER_UPDATE_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
    ])]
    protected ?string $displayName = null;

    #[ORM\Column(
        type: Types::TEXT,
        nullable: true,
    )]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_USER_UPDATE_ITEM,
    ])]
    protected ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_USER_UPDATE_ITEM,
    ])]
    protected ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => Variables::DATE_SERVER,
    ])]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
    ])]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_USER_UPDATE_ITEM,
    ])]
    protected ?int $viewCount = null;

    #[ORM\Column(length: 300, nullable: true)]
    #[ApiGroups([
        Groups::GROUP_USER_READ_COLLECTION,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_USER_CREATE_ITEM,
        Groups::GROUP_USER_UPDATE_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
    ])]
    protected ?string $profilePicture = null;

    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: Stream::class,
        orphanRemoval: true,
    )]
    #[ApiGroups([
        Groups::GROUP_USER_READ_ITEM,
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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(?int $viewCount): self
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): self
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
