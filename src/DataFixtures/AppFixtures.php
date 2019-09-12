<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('admin');
        $user->setPassword('$argon2i$v=19$m=1024,t=1,p=1$c29tZXNhbHQ$orfiTxy8JZXcEECIWsl5ovOarfVtprCem94CIFJlB5s');
        $user->setEmail('admin@admin.fr');
        $user->addRole('ROLE_ADMIN');

        $manager->persist($user);
        $manager->flush();
    }
}
