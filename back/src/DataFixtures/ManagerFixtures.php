<?php

namespace App\DataFixtures;

use App\Entity\Manager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ManagerFixtures extends Fixture
{
    public function __construct(
        public UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /** @var int */
    final public const MANAGER1_REFERENCE = 1;

    public function load(ObjectManager $manager): void
    {
        $myManager = (new Manager())
            ->setEmail('manager@email.local')
            ->setRoles([Manager::ROLE_ADMIN])
        ;

        $myManager->setPassword(
            $this->passwordHasher->hashPassword($myManager, 'azerty'),
        );

        $manager->persist($myManager);
        $manager->flush();

        $this->addReference(self::MANAGER1_REFERENCE, $myManager);
    }
}
