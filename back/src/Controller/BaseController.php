<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $appVersion,
        private readonly array $application,
    ) {
    }

    /**
     * Get app version.
     */
    protected function getAppVersion(): string
    {
        return $this->appVersion;
    }

    /**
     * Get application parameters (services.yaml).
     */
    protected function getApplication(): array
    {
        return $this->application;
    }

    protected function getManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
