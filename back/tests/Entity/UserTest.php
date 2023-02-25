<?php

namespace App\Tests\Entity;

use App\Entity\Stream;
use App\Entity\User;
use App\Utils\Constants\Variables;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \
 */
class UserTest extends TestCase
{
    public function testCreate(): void
    {
        $user = (new User())
            ->setId(3)
            ->setCreatedAt(new DateTimeImmutable('2021-01-02T05:00:00'))
            ->setEmail('test@email.fr')
            ->setLogin('user1')
            ->setDisplayName('User1')
            ->setViewCount(300)
            ->setProfilePicture('image.jpg')
            ->setDescription('User1 description')
        ;

        self::assertEquals(3, $user->getId());
        self::assertEquals('2021-01-02T05:00:00', $user->getCreatedAt()->format(Variables::DATE_TIME_SERVER));
        self::assertEquals(300, $user->getViewCount());
        self::assertEquals('User1', $user->getDisplayName());
        self::assertEquals('user1', $user->getLogin());
        self::assertEquals('User1 description', $user->getDescription());
        self::assertEquals('test@email.fr', $user->getEmail());
        self::assertEquals('image.jpg', $user->getProfilePicture());

        $stream = (new Stream())
            ->setId(2)
            ->setTitle('Stream1')
        ;

        $user->addStream($stream);

        self::assertEquals(1, $user->getStreams()->count());

        $user->removeStream($stream);

        self::assertEquals(0, $user->getStreams()->count());
    }
}
