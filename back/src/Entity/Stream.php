<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\StreamRepository;
use App\Utils\Constants\Groups;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups as ApiGroups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: StreamRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    Groups::GROUP_STREAM_READ_COLLECTION,
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
        new Patch(),
    ],
    normalizationContext: [
        'groups' => [
            Groups::GROUP_STREAM_READ_ITEM,
        ],
    ],
    denormalizationContext: [
        'groups' => [
            Groups::GROUP_STREAM_CREATE_ITEM,
            Groups::GROUP_STREAM_UPDATE_ITEM,
        ],
    ],
)]
class Stream
{
    /** @var int */
    final public const TYPE_LIVE = 1;

    #[ORM\Id]
    #[ORM\Column]
    #[ApiGroups([
        Groups::GROUP_STREAM_READ_COLLECTION,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_STREAM_CREATE_ITEM,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
    ])]
    protected ?int $id = null;

    #[ORM\Column]
    #[ApiGroups([
        Groups::GROUP_STREAM_READ_COLLECTION,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_STREAM_CREATE_ITEM,
        Groups::GROUP_STREAM_UPDATE_ITEM,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
    ])]
    protected ?int $type = null;

    #[ORM\Column(length: 255)]
    #[ApiGroups([
        Groups::GROUP_STREAM_READ_COLLECTION,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_STREAM_CREATE_ITEM,
        Groups::GROUP_STREAM_UPDATE_ITEM,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
    ])]
    protected ?string $title = null;

    #[ORM\Column]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => Variables::DATE_TIME_SERVER,
    ])]
    #[ApiGroups([
        Groups::GROUP_STREAM_READ_COLLECTION,
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_STREAM_CREATE_ITEM,
        Groups::GROUP_USER_READ_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
    ])]
    protected ?DateTimeImmutable $startAt = null;

    #[ORM\ManyToMany(
        targetEntity: Game::class,
        inversedBy: 'streams',
    )]
    #[ApiGroups([
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_USER_READ_ITEM,
    ])]
    protected ?Collection $games = null;

    #[ORM\ManyToOne(inversedBy: 'streams')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiGroups([
        Groups::GROUP_STREAM_READ_ITEM,
        Groups::GROUP_STREAM_CREATE_ITEM,
        Groups::GROUP_GAME_READ_ITEM,
    ])]
    protected ?User $user = null;

    public function __construct()
    {
        $this->games = new ArrayCollection();
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartAt(): ?DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * @return Collection<Game>
     */
    public function getGames(): Collection
    {
        return $this->games ??= new ArrayCollection();
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        $this->games->removeElement($game);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
