<?php

namespace App\DataFixtures;

use App\Entity\TypeMedia;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeMediaFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $type = new TypeMedia();
        $type->setName('Audio');
        $manager->persist($type);

        $type = new TypeMedia();
        $type->setName('Video');
        $manager->persist($type);

        $manager->flush();
    }
}
