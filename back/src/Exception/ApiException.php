<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

class ApiException extends RuntimeException
{
    public function __construct(
        string $message = 'Internal server error',
        ?int $code = null,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code ?? 500, $previous);
    }
}
