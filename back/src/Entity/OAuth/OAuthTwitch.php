<?php

namespace App\Entity\OAuth;

use DateTimeImmutable;
use Symfony\Component\Serializer\Annotation\Groups;

class OAuthTwitch
{
    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?string $state = null;

    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?string $idToken = null;

    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?string $accessToken = null;

    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?string $refreshToken = null;

    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?int $expiresIn = null;

    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?DateTimeImmutable $createdAt = null;

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(?int $expiresIn): static
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function reset(): static
    {
        $this->state = null;
        $this->accessToken = null;
        $this->refreshToken = null;
        $this->expiresIn = null;
        $this->createdAt = null;

        return $this;
    }

    public function assign(array $options): static
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

    public static function fromArray(?array $options): static
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
