<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

class TwitchException extends RuntimeException
{
    public function __construct(
        string $message = 'Twitch internal server error',
        ?int $code = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code ?? 500, $previous);
    }
}
