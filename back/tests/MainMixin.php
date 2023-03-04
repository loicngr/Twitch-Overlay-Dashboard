<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Manager;
use App\Entity\User;
use App\Repository\ManagerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Throwable;

/**
 * @extends ApiTestCase
 */
trait MainMixin
{
    public ?Client $client = null;

    public static array $LOGIN_MANAGER1 = [
        'email' => 'manager@email.local',
        'password' => 'azerty',
    ];

    private static function resetDatabase(): void
    {
        $process = new Process(['composer', 'tests-reset']);
        $process->run();
    }

    protected function tearDown(): void
    {
        // Drop database and reset fixtures between each tests
        // I think is it not a good way to do it, but for now it's work.
        self::resetDatabase();
    }

    public function getEm(): EntityManagerInterface
    {
        return $this->client->getKernel()->getContainer()->get('doctrine')->getManager();
    }

    public function logIn(
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
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/ld+json',
                ],
            ]);
        } catch (Throwable $exception) {
            self::fail($exception->getMessage());
        }

        $this->client = $client;

        return $client;
    }

    /**
     * Proxy get method.
     */
    public function get(string $path): void
    {
        try {
            $this->client->request(Request::METHOD_GET, $path);
        } catch (Throwable $throwable) {
            self::fail($throwable->getMessage());
        }
    }

    /**
     * Proxy post method.
     */
    public function post(string $path, array $json): void
    {
        try {
            $this->client->request(Request::METHOD_POST, $path, [
                'json' => $json,
            ]);
        } catch (Throwable $throwable) {
            self::fail($throwable->getMessage());
        }
    }

    /**
     * Proxy patch method.
     */
    public function patch(string $path, array $json): void
    {
        try {
            $this->client->request(Request::METHOD_PATCH, $path, [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/merge-patch+json',
                ],
                'json' => $json,
            ]);
        } catch (Throwable $throwable) {
            self::fail($throwable->getMessage());
        }
    }

    public static function assertAuthRequired(
        string $path,
        string $method = Request::METHOD_GET,
    ): void {
        $client = static::createClient();
        $client->request($method, $path);
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function getUserById(int $id): ?User
    {
        $em = $this->getEm();
        $em->clear();

        /** @var UserRepository $rpUser */
        $rpUser = $em->getRepository(User::class);

        return $rpUser->find($id);
    }

    public function getManagerById(int $id): ?Manager
    {
        $em = $this->getEm();
        $em->clear();

        /** @var ManagerRepository $rpManager */
        $rpManager = $em->getRepository(Manager::class);

        return $rpManager->find($id);
    }
}
