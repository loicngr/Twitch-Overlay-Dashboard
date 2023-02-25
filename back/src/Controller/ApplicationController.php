<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    public function __construct(
        private readonly string $appVersion,
        private readonly array $application,
    ) {
    }

    #[Route('/', name: 'root')]
    public function getRoot(): Response
    {
        return $this->redirect($this->application['url']['front']);
    }

    #[Route('/version', name: 'version', methods: Request::METHOD_GET)]
    public function getVersion(): Response
    {
        return $this->json([
            'version' => $this->appVersion,
        ]);
    }
}
