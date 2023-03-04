<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    'game:read:collection',
                ],
            ],
        ),
        new Post(
            validationContext: [
                'groups' => [
                    'Default',
                    'game:create:item',
                ],
            ],
        ),
        new Patch(
            validationContext: [
                'groups' => [
                    'Default',
                    'game:update:item',
                ],
            ],
        ),
    ],
    normalizationContext: [
        'groups' => [
            'game:read:item',
        ],
    ],
    denormalizationContext: [
        'groups' => [
            'game:create:item',
            'game:update:item',
        ],
    ],
)]
class Game
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups([
        'game:read:collection',
        'game:read:item',
        'game:create:item',
        'user:read:item',
        'stream:read:item',
    ])]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        groups: [
            'game:create:item',
            'stream:create:item',
            'game:update:item',
        ],
    )]
    #[Groups([
        'game:read:collection',
        'game:read:item',
        'game:create:item',
        'game:update:item',
        'stream:read:item',
        'user:read:item',
    ])]
    protected ?string $name = null;

    #[ORM\Column(length: 300)]
    #[Groups([
        'game:read:collection',
        'game:read:item',
        'game:create:item',
        'game:update:item',
        'stream:read:item',
        'user:read:item',
    ])]
    protected ?string $picture = null;

    #[ORM\Column(nullable: true)]
    #[Groups([
        'game:read:collection',
        'game:read:item',
        'game:create:item',
        'game:update:item',
        'stream:read:item',
        'user:read:item',
    ])]
    protected ?int $igdbId = null;

    #[ORM\ManyToMany(
        targetEntity: Stream::class,
        mappedBy: 'games',
    )]
    #[Groups([
        'game:read:item',
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getIgdbId(): ?int
    {
        return $this->igdbId;
    }

    public function setIgdbId(?int $igdbId): static
    {
        $this->igdbId = $igdbId;

        return $this;
    }

    /**
     * @return Collection<Stream>
     */
    public function getStreams(): Collection
    {
        return $this->streams ??= new ArrayCollection();
    }

    public function addStream(Stream $stream): static
    {
        if (!$this->streams->contains($stream)) {
            $this->streams->add($stream);
            $stream->addGame($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): static
    {
        if ($this->streams->removeElement($stream)) {
            $stream->removeGame($this);
        }

        return $this;
    }
}
