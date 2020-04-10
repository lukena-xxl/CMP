<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $encoder;
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag, UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->parameterBag = $parameterBag;
    }

    public function load(ObjectManager $manager)
    {
        $factory = new Factory();
        $faker = $factory->create('en_EN');

        $roles = $this->parameterBag->get('user_roles');
        $users = [];

        foreach ($roles as $role) {
            $user = new User();

            $parsRole = explode('ROLE_', $role);
            $login = strtolower($parsRole[1]);

            $user->setLogin($login);
            $user->setRoles([$role]);
            $user->setEmail($faker->email);
            $user->setPhone($faker->e164PhoneNumber);
            $user->setFirstName($faker->firstName);

            $password = $this->encoder->encodePassword($user, '123456');
            $user->setPassword($password);

            $user->setRegistrationDate($faker->dateTimeBetween('-17 years', 'now'));
            $user->setBirthDate($faker->dateTimeBetween('-60 years', '-18 years'));

            $manager->persist($user);

            $users[] = $user;
        }

        $manager->flush();

        $this->addReference('users', (object)$users);
    }
}
