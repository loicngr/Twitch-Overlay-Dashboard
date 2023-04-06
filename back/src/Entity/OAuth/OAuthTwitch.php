<?php

namespace App\Entity\OAuth;

use App\Utils\Constants\Groups;
use Symfony\Component\Serializer\Annotation\Groups as ApiGroups;

class OAuthTwitch
{
    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?string $state = null;

    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?int $refreshRetryCount = null;

    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?string $accessToken = null;

    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?string $refreshToken = null;

    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?int $expiresIn = null;

    #[ApiGroups([
        Groups::GROUP_MANAGER_READ_ITEM,
        Groups::GROUP_MANAGER_UPDATE_ITEM,
    ])]
    protected ?string $createdAt = null;

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?int $expiresIn): self
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRefreshRetryCount(): int
    {
        return $this->refreshRetryCount ?? 0;
    }

    public function setRefreshRetryCount(int $refreshRetryCount = 0): self
    {
        $this->refreshRetryCount = $refreshRetryCount;

        return $this;
    }

    public function addRefreshRetryCount(int $count = 1): self
    {
        $this->refreshRetryCount += $count;

        return $this;
    }

    public function reset(): self
    {
        $this->state = null;
        $this->accessToken = null;
        $this->refreshToken = null;
        $this->expiresIn = null;
        $this->createdAt = null;

        return $this;
    }

    public function assign(array $options): self
    {
        if (
            isset(
                $options['access_token'],
                $options['refresh_token'],
                $options['expires_in'],
                $options['created_at']
            )
        ) {
            $this->accessToken = $options['access_token'];
            $this->refreshToken = $options['refresh_token'];
            $this->expiresIn = $options['expires_in'];
            $this->createdAt = $options['created_at'];
        }

        return $this;
    }

    public function getProperties(): array
    {
        $output = [];

        foreach ($this as $key => $value) {
            $output[$key] = $value;
        }

        return $output;
    }

    public static function fromArray(?array $options): self
    {
        $class = new static();

        foreach ($class->getProperties() as $key => $value) {
            if (!isset($options[$key])) {
                continue;
            }

            $class->{$key} = $options[$key];
        }

        return $class;
    }

    public function toArray(): array
    {
        $output = [];

        foreach ($this->getProperties() as $key => $value) {
            if (null === $value) {
                continue;
            }

            $output[$key] = $value;
        }

        return $output;
    }
}
