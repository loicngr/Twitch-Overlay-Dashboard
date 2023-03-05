<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\MainMixin;
use Throwable;

/**
 * @internal
 *
 * @coversDefaultClass /
 */
class OAuthControllerTest extends ApiTestCase
{
    use MainMixin;

    /**
     * @throws Throwable
     */
    public function testOauthGenerateState(): void
    {
        $path = '/oauth/state';
        self::assertAuthRequired($path);

        $this->logIn();
        $this->get($path);
        $this->assertResponseIsSuccessful();

        $manager = $this->getManagerById(1);
        $managerState = $manager->getManagerSettingsFeature()->getTwitchOAuth()->getState();

        self::assertIsString($managerState);

        $state = ['state' => $managerState];
        $this->assertJsonEquals($state);
    }

    public function testOauthTwitch(): void
    {
    }
}
