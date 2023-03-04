<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\StreamRepository;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: StreamRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(
            normalizationContext: [
                'groups' => [
                    'stream:read:collection',
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
        new Patch(),
    ],
    normalizationContext: [
        'groups' => [
            'stream:read:item',
        ],
    ],
    denormalizationContext: [
        'groups' => [
            'stream:create:item',
            'stream:update:item',
        ],
    ],
)]
class Stream
{
    /** @var int */
    final public const TYPE_LIVE = 1;

    #[ORM\Id]
    #[ORM\Column]
    #[Groups([
        'stream:read:collection',
        'stream:read:item',
        'stream:create:item',
        'user:read:item',
        'game:read:item',
    ])]
    protected ?int $id = null;

    #[ORM\Column]
    #[Groups([
        'stream:read:collection',
        'stream:read:item',
        'stream:create:item',
        'stream:update:item',
        'user:read:item',
        'game:read:item',
    ])]
    protected ?int $type = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'stream:read:collection',
        'stream:read:item',
        'stream:create:item',
        'stream:update:item',
        'user:read:item',
        'game:read:item',
    ])]
    protected ?string $title = null;

    #[ORM\Column]
    #[Context([
        DateTimeNormalizer::FORMAT_KEY => Variables::DATE_TIME_SERVER,
    ])]
    #[Groups([
        'stream:read:collection',
        'stream:read:item',
        'stream:create:item',
        'user:read:item',
        'game:read:item',
    ])]
    protected ?DateTimeImmutable $startAt = null;

    #[ORM\ManyToMany(
        targetEntity: Game::class,
        inversedBy: 'streams',
    )]
    #[Groups([
        'stream:read:item',
        'user:read:item',
    ])]
    protected ?Collection $games = null;

    #[ORM\ManyToOne(inversedBy: 'streams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'stream:read:item',
        'game:read:item',
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

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getStartAt(): ?DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeImmutable $startAt): static
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

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        $this->games->removeElement($game);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
