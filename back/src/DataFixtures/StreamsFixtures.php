<?php

namespace App\DataFixtures;

use App\Entity\Stream;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StreamsFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var string */
    final public const STREAM1_REFERENCE = 'STREAM-1';

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UsersFixtures::USER1_REFERENCE);

        $stream = (new Stream())
            ->setId(1)
            ->setTitle('Stream du matin 1')
            ->setUser($user)
            ->setStartAt(new DateTimeImmutable('2022-01-01T08:00:00'))
            ->setType(Stream::TYPE_LIVE)
        ;

        $manager->persist($stream);
        $manager->flush();

        $this->addReference(self::STREAM1_REFERENCE, $stream);
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
        ];
    }
}
