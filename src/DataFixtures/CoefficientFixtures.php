<?php

namespace App\DataFixtures;

use App\Entity\Coefficient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;


class CoefficientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /*$factory = new Factory();
        $faker = $factory->create('en_EN');

        $coefficient = new Coefficient();

        $coefficient->setName($faker->text(40));
        $coefficient->setRatio($faker->randomFloat());
        $coefficient->setUpdateDate($faker->dateTime('now'));

        $users = (array)$this->getReference('users');
        $coefficient->setUser($users[array_rand($users)]);

        $manager->persist($coefficient);
        $manager->flush();*/

    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
