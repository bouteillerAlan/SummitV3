<?php

namespace App\Factory;

use App\Entity\User;
use App\Enums\RolesEnums;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<User>
 */
final class UserFactory extends PersistentProxyObjectFactory
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function class(): string
    {
        return User::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'password' => self::faker()->password(),
            'roles' => [RolesEnums::ROLE_USER],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(User $user): void {
                error_log($user->getEmail());
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
            })
        ;
    }
}
