<?php

namespace App\Tests\ApiTests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Throwable;

/**
 * @extends ApiTestCase
 */
trait Mixin
{
    public static array $LOGIN_MANAGER1 = [
        'email' => 'manager@email.local',
        'password' => 'azerty',
    ];

    public static function logIn(
        ?string $email = null,
        ?string $password = null,
    ): Client {
        if (null === $email || null === $password) {
            $email = self::$LOGIN_MANAGER1['email'];
            $password = self::$LOGIN_MANAGER1['password'];
        }

        $client = static::createClient();

        try {
            $crawler = $client->request(
                'POST',
                '/login_check',
                [
                    'headers' => ['accept' => 'application/json'],
                    'json' => [
                        'email' => $email,
                        'password' => $password,
                    ],
                ],
            );

            $data = json_decode($crawler->getContent(), true);

            $client->setDefaultOptions([
                'auth_bearer' => $data['token'],
                'headers' => ['accept' => 'application/json'],
            ]);
        } catch (Throwable $exception) {
            self::fail($exception->getMessage());
        }

        return $client;
    }

    public function getUserById(int $id): ?User
    {
        $userRp = static::getContainer()->get(UserRepository::class);

        return $userRp->find($id);
    }
}
