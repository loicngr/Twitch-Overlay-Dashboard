<?php

namespace App\Entity;

use App\Entity\OAuth\OAuthTwitch;
use App\Repository\ManagerSettingsFeatureRepository;
use App\Utils\Constants\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as ApiGroups;

#[ORM\Entity(repositoryClass: ManagerSettingsFeatureRepository::class)]
class ManagerSettingsFeature
{
    #[ORM\Id]
    #[ORM\OneToOne(
        inversedBy: 'managerSettingsFeature',
        targetEntity: Manager::class,
    )]
    protected ?Manager $manager = null;

    #[ORM\Column(
        type: 'json',
        options: ['default' => '[]'],
    )]
    protected array $twitchOAuth = [];

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(Manager $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
    ])]
    public function getTwitchOAuth(): OAuthTwitch
    {
        return OAuthTwitch::fromArray($this->twitchOAuth);
    }

    public function setTwitchOAuth(OAuthTwitch $twitchOAuth): self
    {
        $this->twitchOAuth = $twitchOAuth->toArray();

        return $this;
    }
}
