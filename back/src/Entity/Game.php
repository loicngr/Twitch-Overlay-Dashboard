<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\GameRepository;
use App\Utils\Constants\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as ApiGroups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    Groups::GROUP_GAME_READ_COLLECTION,
                ],
            ],
        ),
        new Post(
            validationContext: [
                'groups' => [
                    'Default',
                    Groups::GROUP_GAME_CREATE_ITEM,
                ],
            ],
        ),
        new Patch(
            validationContext: [
                'groups' => [
                    'Default',
                    Groups::GROUP_GAME_UPDATE_ITEM,
                ],
            ],
        ),
    ],
    normalizationContext: [
        'groups' => [
            Groups::GROUP_GAME_READ_ITEM,
        ],
    ],
    denormalizationContext: [
        'groups' => [
            Groups::GROUP_GAME_CREATE_ITEM,
            Groups::GROUP_GAME_UPDATE_ITEM,
        ],
    ],
)]
class Game
{
    #[ORM\Id]
    #[ORM\Column]
    #[ApiGroups([
        Groups::GROUP_GAME_READ_COLLECTION,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_GAME_CREATE_ITEM,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
    ])]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        groups: [
            Groups::GROUP_GAME_CREATE_ITEM,
            Groups::GROUP_STREAM_CREATE_ITEM,
            Groups::GROUP_GAME_UPDATE_ITEM,
        ],
    )]
    #[ApiGroups([
        Groups::GROUP_GAME_READ_COLLECTION,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_GAME_CREATE_ITEM,
        Groups::GROUP_GAME_UPDATE_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_USER_READ_ITEM,
    ])]
    protected ?string $name = null;

    #[ORM\Column(length: 300)]
    #[ApiGroups([
        Groups::GROUP_GAME_READ_COLLECTION,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_GAME_CREATE_ITEM,
        Groups::GROUP_GAME_UPDATE_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_USER_READ_ITEM,
    ])]
    protected ?string $picture = null;

    #[ORM\Column(nullable: true)]
    #[ApiGroups([
        Groups::GROUP_GAME_READ_COLLECTION,
        Groups::GROUP_GAME_READ_ITEM,
        Groups::GROUP_GAME_CREATE_ITEM,
        Groups::GROUP_GAME_UPDATE_ITEM,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_USER_READ_ITEM,
    ])]
    protected ?int $igdbId = null;

    #[ORM\ManyToMany(
        targetEntity: Stream::class,
        mappedBy: 'games',
    )]
    #[ApiGroups([
        Groups::GROUP_GAME_READ_ITEM,
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getIgdbId(): ?int
    {
        return $this->igdbId;
    }

    public function setIgdbId(?int $igdbId): self
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

    public function addStream(Stream $stream): self
    {
        if (!$this->streams->contains($stream)) {
            $this->streams->add($stream);
            $stream->addGame($this);
        }

        return $this;
    }

    public function removeStream(Stream $stream): self
    {
        if ($this->streams->removeElement($stream)) {
            $stream->removeGame($this);
        }

        return $this;
    }
}
