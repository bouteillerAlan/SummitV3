<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixtures;
use App\Entity\Training;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class TrainingFixtures extends BaseFixtures
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Training::class, 10, function (Training $training, $count) {
            $training->setDate($this->faker->dateTime());
            $training->setName($this->faker->name());
            $training->setUser($this->getReference());
            // todo: 'User_' . $this->faker->randomDigitNotNull()
        });
    }
}