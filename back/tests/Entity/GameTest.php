<?php

namespace App\Tests\Entity;

use App\Entity\Game;
use App\Entity\Stream;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass /
 */
class GameTest extends TestCase
{
    public function testCreate(): void
    {
        $game = (new Game())
            ->setId(1)
            ->setName('Factorio')
            ->setPicture('picture.jpg')
            ->setIgdbId(25664)
        ;

        self::assertEquals(1, $game->getId());
        self::assertEquals('Factorio', $game->getName());
        self::assertEquals('picture.jpg', $game->getPicture());
        self::assertEquals(25664, $game->getIgdbId());

        $user = (new User())
            ->setId(1)
            ->setCreatedAt(new DateTimeImmutable('2021-01-02T05:00:00'))
            ->setEmail('test@email.fr')
            ->setLogin('user1')
            ->setDisplayName('User1')
        ;

        $stream = (new Stream())
            ->setId(1)
            ->setTitle('Stream1')
            ->setUser($user)
            ->setType(Stream::TYPE_LIVE)
            ->setStartAt(new DateTimeImmutable('2023-02-05T06:15:00'))
        ;

        $game->addStream($stream);

        self::assertEquals(1, $game->getStreams()->count());
        self::assertEquals(1, $stream->getGames()->count());

        $game->removeStream($stream);

        self::assertEquals(0, $game->getStreams()->count());
    }
}
