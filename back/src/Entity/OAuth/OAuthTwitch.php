<?php

namespace App\Entity\OAuth;

use Symfony\Component\Serializer\Annotation\Groups;

class OAuthTwitch
{
    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?string $token = null;

    #[Groups([
        'manager:read:item',
        'manager:update:item',
    ])]
    protected ?string $refreshToken = null;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

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
