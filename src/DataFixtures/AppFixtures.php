<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setUsername("Admin");
        $user->setEmail('admin@portfolio.com');
        $user->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);
        $manager->persist($user);

        $manager->flush();
    }
}
