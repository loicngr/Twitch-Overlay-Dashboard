<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    /** @var string */
    final public const USER1_REFERENCE = 'USER-1';

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setId(1)
            ->setEmail('user1@email.local')
            ->setDescription('description')
            ->setLogin('user1')
            ->setDisplayName('User1')
            ->setViewCount(0)
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER1_REFERENCE, $user);
    }
}
