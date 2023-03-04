<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends BaseController
{
    #[Route('/', name: 'root')]
    public function getRoot(): Response
    {
        return $this->redirect($this->getApplication()['url']['front']);
    }

    #[Route('/version', name: 'version', methods: Request::METHOD_GET)]
    public function getVersion(): Response
    {
        return $this->json([
            'version' => $this->getAppVersion(),
        ]);
    }
}
