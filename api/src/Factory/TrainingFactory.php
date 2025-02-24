<?php

namespace App\Factory;

use App\Entity\Training;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Training>
 */
final class TrainingFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Training::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function defaults(): array|callable
    {
        // todo: create random from 0 to 50 training for each user
        //       actually this code create 500 users + the 1k from the user factory
        return [
            'date' => self::faker()->dateTime(),
            'name' => self::faker()->sentence(3),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Training $training): void {})
        ;
    }
}
