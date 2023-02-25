<?php

namespace App\DataFixtures;

use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GamesFixtures extends Fixture
{
    /** @var string */
    final public const GAME1_REFERENCE = 'GAME-1';

    public function load(ObjectManager $manager): void
    {
        $game = (new Game())
            ->setId(1)
            ->setName('Factorio')
            ->setPicture('https://static-cdn.jtvnw.net/ttv-boxart/130942_IGDB-144x192.jpg')
        ;

        $manager->persist($game);
        $manager->flush();

        $this->addReference(self::GAME1_REFERENCE, $game);
    }
}
