<?php

namespace App\Tests\Entity;

use App\Entity\Game;
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
class StreamTest extends TestCase
{
    public function testCreate(): void
    {
        $user = (new User())
            ->setId(1)
            ->setCreatedAt(new DateTimeImmutable('2021-01-02T05:00:00'))
            ->setEmail('test@email.fr')
            ->setLogin('user1')
            ->setDisplayName('User1')
        ;

        $stream = (new Stream())
            ->setId(2)
            ->setTitle('Stream1')
            ->setUser($user)
            ->setType(Stream::TYPE_LIVE)
            ->setStartAt(new DateTimeImmutable('2023-02-05T06:15:00'))
        ;

        self::assertEquals(2, $stream->getId());
        self::assertEquals('Stream1', $stream->getTitle());
        self::assertEquals(1, $stream->getUser()->getId());
        self::assertEquals('2023-02-05T06:15:00', $stream->getStartAt()->format(Variables::DATE_TIME_SERVER));
        self::assertEquals(Stream::TYPE_LIVE, $stream->getType());

        $game = (new Game())
            ->setId(2)
            ->setName('Game2')
        ;

        $stream->addGame($game);

        self::assertEquals(1, $stream->getGames()->count());

        $stream->removeGame($game);

        self::assertEquals(0, $stream->getGames()->count());
    }
}
