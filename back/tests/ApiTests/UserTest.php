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
}
