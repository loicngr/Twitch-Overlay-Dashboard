<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\StreamRepository;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[
    ORM\Entity(repositoryClass: StreamRepository::class),
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
class Stream
{
    /** @var int */
    final public const TYPE_LIVE = 1;

    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column,
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?int $id = null;

    #[
        ORM\ManyToOne(inversedBy: 'streams'),
        ORM\JoinColumn(nullable: false),
        Groups([
            'read:item',
        ]),
    ]
    protected ?User $user = null;

    #[
        ORM\Column,
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?int $type = null;

    #[
        ORM\Column(length: 255),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?string $title = null;

    #[
        ORM\Column,
        Context([DateTimeNormalizer::FORMAT_KEY => Variables::DATE_TIME_SERVER]),
        Groups([
            'read:collection',
            'read:item',
        ]),
    ]
    protected ?DateTimeImmutable $startAt = null;

    #[
        ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'streams'),
        Groups([
            'read:item',
        ]),
    ]
    protected ?Collection $games = null;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
}
