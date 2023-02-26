<?php

namespace App\Tests\ApiTests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
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
    public function testSomething(): void
    {
        $client = self::logIn();

        $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();

        $user1 = self::getUserById(1);
        $expectedUser1 = [
            'id' => 1,
            'login' => 'user1',
            'displayName' => 'User1',
            'description' => 'description',
            'email' => 'user1@email.local',
            'createdAt' => $user1->getCreatedAt()->format(Variables::DATE_TIME_SERVER) . '+00:00',
            'viewCount' => 0,
        ];

        $this->assertJsonContains([
            $expectedUser1,
        ]);
    }
}
