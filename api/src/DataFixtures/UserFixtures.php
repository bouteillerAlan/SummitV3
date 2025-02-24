<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 10, function (User $user, $count) {
            $user->setEmail($this->faker->email());
            $user->setPassword($this->faker->password());
        });
    }
}
