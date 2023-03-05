<?php

namespace App\Controller;

use App\Entity\Manager;
use App\Exception\ApiException;
use App\Repository\ManagerRepository;
use App\Service\OAuthService;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class OAuthController extends BaseController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $appVersion,
        private readonly array $application,
        private readonly LoggerInterface $logger,
        private readonly HttpClientInterface $httpClient,
        private readonly OAuthService $authService,
    ) {
        parent::__construct($entityManager, $appVersion, $application);
    }

    #[Route(
        path: '/oauth/state',
        name: 'oauth_state',
        methods: Request::METHOD_GET,
    )]
    public function oauthGenerateState(
        #[CurrentUser] ?Manager $manager,
    ): Response {
        if (!$manager instanceof Manager) {
            throw new ApiException("Can't find you.");
        }

        $state = sprintf(
            '%s-%s',
            (string) $manager->getId(),
            $this->authService->generateOAuthState(30),
        );

        $managerSettingsFeature = $manager->getManagerSettingsFeature();
        $twitchOauth = $managerSettingsFeature->getTwitchOAuth();
        $twitchOauth->setState($state);
        $managerSettingsFeature->setTwitchOAuth($twitchOauth);

        $em = $this->getManager();
        $em->persist($managerSettingsFeature);
        $em->flush();

        return $this->json([
            'state' => $state,
        ]);
    }

    #[Route(
        path: '/oauth/twitch',
        name: 'oauth_twitch',
        methods: Request::METHOD_GET,
    )]
    public function oauthTwitch(
        Request $request,
    ): RedirectResponse {
        $this->logger->error('oauthTwitch');
        $application = $this->getApplication();
        $failedUrl = sprintf('%s/error-oauth', $application['url']['front']);

        $cbError = fn (string $message = '') => $this->redirect(sprintf('%s?message=%s', $failedUrl, $message));

        $code = $request->query->get('code') ?? null;
        $state = $request->query->get('state') ?? null;
        $error = $request->query->get('error') ?? false;

        if ($error) {
            return $cbError($request->query->get('error_description') ?? 'Invalid request');
        }

        if (null === $code) {
            return $cbError('Invalid request');
        }

        $em = $this->getManager();
        /** @var ManagerRepository $rpManager */
        $rpManager = $em->getRepository(Manager::class);

        $manager = $rpManager->findManagerFromTwitchState($state);

        if (!$manager instanceof Manager) {
            return $cbError("Can't find you");
        }

        $managerSettingsFeature = $manager->getManagerSettingsFeature();
        $twitchOauth = $managerSettingsFeature->getTwitchOAuth();

        if ($twitchOauth->getState() !== $state) {
            return $cbError('Invalid request');
        }

        $url = sprintf('%s/token', Variables::OAUTH_TWITCH_BASE_URL);
        $params = [
            'client_id' => $application['oauth']['twitch']['clientId'],
            'client_secret' => $application['oauth']['twitch']['secret'],
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $application['oauth']['twitch']['redirectUrl'],
        ];

        try {
            $response = $this->httpClient->request(
                Request::METHOD_POST,
                $url,
                [
                    'body' => $params,
                ],
            );

            $statusCode = $response->getStatusCode();

            if (Response::HTTP_OK !== $statusCode) {
                $this->logger->error($response->getContent());
                $this->logger->error($statusCode);

                return $cbError('Redirection flow error status');
            }

            $content = $response->toArray();
        } catch (Throwable $e) {
            return $cbError($e->getMessage());
        }

        if (!isset($content['access_token'])) {
            $this->logger->debug($response->getContent());

            return $cbError('Redirection flow error');
        }

        $content['created_at'] = new DateTimeImmutable('now');

        $twitchOauth
            ->reset()
            ->assign($content)
        ;
        $managerSettingsFeature->setTwitchOAuth($twitchOauth);

        $em->persist($managerSettingsFeature);
        $em->flush();

        return $this->redirect($application['url']['front']);
    }
}
