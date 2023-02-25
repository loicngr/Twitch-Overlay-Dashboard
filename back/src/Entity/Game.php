<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 300)]
    protected ?string $picture = null;

    #[ORM\Column(nullable: true)]
    protected ?int $igdbId = null;

    #[ORM\ManyToMany(targetEntity: Stream::class, mappedBy: 'games')]
    protected ?Collection $streams = null;

    public function __construct()
    {
        $this->streams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
