<?php

namespace App\DataFixtures;

use App\Factory\TrainingFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrainingFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        TrainingFactory::createMany(500);
    }
}