<?php

namespace Testing\Fixtures;

use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use \Model\EProfilo;
use Ramsey\Uuid\Type\Integer;
use TechnicalServiceLayer\Roles\Roles;

class UserFixture extends AbstractFixture
{
    /**
     * @throws InvalidEmailException
     * @throws TooManyRequestsException
     * @throws AuthError
     * @throws UserAlreadyExistsException
     * @throws InvalidPasswordException
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');

        for ($i = 0; $i < 20; $i++) {
            $auth = getAuth();
            $email = $faker->email;
            $userId = $auth->register(
                email: $email,
                password: $faker->password(),
                username: $email,
            );
            $auth->admin()->addRoleForUserById($userId, Roles::ADMIN);

            FixtureState::$userIds[$i] = $userId;
        }

        for ($i = 20; $i < 30; $i++) {
            $auth = getAuth();
            $email = $faker->email;
            $userId = $auth->register(
                email: $email,
                password: $faker->password(),
                username: $email,
            );
            $auth->admin()->addRoleForUserById($userId, Roles::LANDLORD);

            FixtureState::$userIds[$i] = $userId;
        }
    }
}
