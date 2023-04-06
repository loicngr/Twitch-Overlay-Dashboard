<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected string $appVersion,
        protected array $application,
    ) {
    }
}
