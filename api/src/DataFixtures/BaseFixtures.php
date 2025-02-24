<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * the base class for all the fixtures class
 */
abstract class BaseFixtures extends Fixture
{
    /** @var ObjectManager */
    private ObjectManager $manager;

    /** @var Generator */
    protected Generator $faker;

    abstract protected function loadData(ObjectManager $manager);

    /**
     * load faker & the manager
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;
        $this->loadData($manager);
    }

    /**
     * create $count instance of $className, you can retrieve it with App\Entity\ClassName_#COUNT#
     * /!\ class call persist then flush
     * @param string $className
     * @param int $count
     * @param callable $factory
     * @return void
     */
    protected function createMany(string $className, int $count, callable $factory): void
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
            $this->addReference($className . '_' . $i, $entity);
        }
        $this->manager->flush();
    }
}