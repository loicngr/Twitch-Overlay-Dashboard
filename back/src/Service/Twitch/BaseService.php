<?php

namespace App\Service\Twitch;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseService
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
        protected readonly array $application,
        protected readonly LoggerInterface $logger,
        protected readonly HttpClientInterface $httpClient,
    ) {
    }
}
