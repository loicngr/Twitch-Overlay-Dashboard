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
    }
}
