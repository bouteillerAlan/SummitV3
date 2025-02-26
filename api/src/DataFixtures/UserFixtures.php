<?php

namespace App\DataFixtures;

use App\Enums\RolesEnums;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //UserFactory::createMany(100); --> created directly via the training factory
        UserFactory::createOne(function() {
            return [
                'email' => 'admin@admin.dev',
                'password' => 'password',
                'roles' => [RolesEnums::ROLE_ADMIN]
            ];
        });
    }
}
