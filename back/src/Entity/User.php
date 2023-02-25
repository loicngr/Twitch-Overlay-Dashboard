<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ApiResource,
    Get(
        normalizationContext: [
            'groups' => [
                'read:item',
            ],
        ],
    ),
    GetCollection(
        normalizationContext: [
            'groups' => [
                'read:collection',
            ],
        ],
    ),
]
class User
{
    #[
        ORM\Id,
        ORM\Column,
        ORM\GeneratedValue,
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?int $id = null;

    #[
        ORM\Column(length: 255),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?string $login = null;

    #[
        ORM\Column(length: 255),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?string $displayName = null;

    #[
        ORM\Column(type: Types::TEXT, nullable: true),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?string $description = null;

    #[
        ORM\Column(length: 255),
        Assert\NotBlank,
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?string $email = null;

    #[
        ORM\Column,
        Assert\NotNull,
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?DateTimeImmutable $createdAt = null;

    #[
        ORM\Column(nullable: true),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?int $viewCount = null;

    #[
        ORM\Column(length: 300, nullable: true),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?string $profilePicture = null;

    #[
        ORM\OneToMany(mappedBy: 'userId', targetEntity: Stream::class, orphanRemoval: true),
        Groups([
            'read:item',
        ]),
    ]
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

    /**
     * @return Collection<Stream>
     */
    public function getStreams(): Collection
    {
        return $this->streams ??= new ArrayCollection();
    }

    public function addStream(Stream $stream): self
    {
        if (!$this->streams->contains($stream)) {
            $this->streams->add($stream);
            $stream->setUser($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): self
    {
        if ($this->streams->removeElement($stream)) {
            // set the owning side to null (unless already changed)
            if ($stream->getUser() === $this) {
                $stream->setUser(null);
            }
        }

        return $this;
    }
}
