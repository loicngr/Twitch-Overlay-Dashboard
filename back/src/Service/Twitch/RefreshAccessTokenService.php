<?php

namespace App\Service\Twitch;

use App\Entity\Manager;
use App\Utils\Constants\Variables;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RefreshAccessTokenService extends BaseService
{
    private function errorPendingRefresh(
        Manager $manager,
    ): bool {
        $manager
            ->getManagerSettingsFeature()
            ->getTwitchOAuth()
            ->addRefreshRetryCount()
        ;

        $this->entityManager->flush();

        return false;
    }

    private function haveExceededMaxRetry(
        Manager $manager,
    ): bool {
        $managerSettingsFeature = $manager->getManagerSettingsFeature();

        if (5 === $managerSettingsFeature->getTwitchOAuth()->getRefreshRetryCount()) {
            $this->logger->error(sprintf('Max retry for: %d', $manager->getId()));

            return true;
        }

        return false;
    }

    private function haveRefreshToken(
        Manager $manager,
    ): bool {
        $managerSettingsFeature = $manager->getManagerSettingsFeature();
        $refreshToken = $managerSettingsFeature->getTwitchOAuth()->getRefreshToken();

        if (null === $refreshToken) {
            $this->logger->error('Refresh token not found');

            return false;
        }

        return true;
    }

    /**
     * Refresh the token with refresh_token when access_token expired.
     */
    public function handleExpiredAccessToken(
        Manager $manager,
    ): bool {
        $managerSettingsFeature = $manager->getManagerSettingsFeature();
        $refreshToken = $managerSettingsFeature->getTwitchOAuth()->getRefreshToken();

        if (!(
            $this->haveExceededMaxRetry($manager)
            && $this->haveRefreshToken($manager)
        )) {
            return false;
        }

        $url = sprintf('%s/token', Variables::OAUTH_TWITCH_BASE_URL);
        $params = [
            'client_id' => $this->application['oauth']['twitch']['clientId'],
            'client_secret' => $this->application['oauth']['twitch']['secret'],
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $doThrow = false;
        $content = [];

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

                if (isset($response->toArray()['message'])) {
                    $this->logger->error($response->toArray()['message']);
                }

                $doThrow = true;
            } else {
                $content = $response->toArray();
            }
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            $doThrow = true;
        }

        if (!isset($content['access_token'], $content['refresh_token'])) {
            $this->logger->debug('Access token or refresh token not received from twitch response');

            $doThrow = true;
        }

        if ($doThrow) {
            return $this->errorPendingRefresh($manager);
        }

        $managerSettingsFeature
            ->getTwitchOAuth()
            ->setAccessToken($content['access_token'])
            ->setRefreshToken($content['refresh_token'])
            ->setRefreshRetryCount()
        ;

        $this->entityManager->flush();

        return true;
    }
}
