<?php

namespace App\Service;

use Symfony\Component\String\ByteString;

class OAuthService
{
    public function generateOAuthState(int $length = 32): string
    {
        return ByteString::fromRandom($length)->toString();
    }
}
