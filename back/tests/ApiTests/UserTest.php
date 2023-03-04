<?php

namespace App\Tests\ApiTests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Stream;
use App\Utils\Constants\Variables;
use Throwable;

/**
 * @internal
 *
 * @coversNothing
 */
class UserTest extends ApiTestCase
{
    use Mixin;

    /**
     * @throws Throwable
     */
    public function testFetchUsers(): void
    {
        $path = '/users';
        self::assertAuthRequired($path);

        $this->logIn();
        $this->get($path);
        $this->assertResponseIsSuccessful();

        $user1 = self::getUserById(1);
        $expectedUser1 = [
            'id' => 1,
            'login' => 'user1',
            'displayName' => 'User1',
            'description' => 'description',
            'email' => 'user1@email.local',
            'createdAt' => $user1->getCreatedAt()->format(Variables::DATE_SERVER),
            'viewCount' => 0,
        ];

        $this->assertJsonEquals([
            $expectedUser1,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function testGetUser(): void
    {
        $path = '/users/1';
        self::assertAuthRequired($path);

        $this->logIn();
        $this->get($path);
        $this->assertResponseIsSuccessful();

        $user1 = self::getUserById(1);
        $expectedUser1 = [
            'id' => 1,
            'login' => 'user1',
            'displayName' => 'User1',
            'description' => 'description',
            'email' => 'user1@email.local',
            'createdAt' => $user1->getCreatedAt()->format(Variables::DATE_SERVER),
            'viewCount' => 0,
            'streams' => [
                [
                    'id' => 1,
                    'type' => Stream::TYPE_LIVE,
                    'title' => 'Stream du matin 1',
                    'startAt' => '2022-01-01T08:00:00',
                    'games' => [],
                ],
            ],
        ];

        self::assertJsonEquals($expectedUser1);
    }

    /**
     * @throws Throwable
     */
    public function testCreateUser(): void
    {
        $path = '/users';
        self::assertAuthRequired($path);

        $this->logIn();

        $options = [
            'id' => 654234,
            'login' => 'test_login',
            'displayName' => 'testLogin',
            'description' => 'test description',
            'email' => 'usertest@email.fr',
            'createdAt' => '2023-02-03',
            'viewCount' => 35000,
        ];

        $this->post($path, $options);
        $this->assertResponseIsSuccessful();

        $expectedUser = [
            'id' => 654234,
            'login' => 'test_login',
            'displayName' => 'testLogin',
            'description' => 'test description',
            'email' => 'usertest@email.fr',
            'createdAt' => '2023-02-03',
            'viewCount' => 35000,
            'streams' => [],
        ];

        $this->assertJsonEquals($expectedUser);
    }
}
