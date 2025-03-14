<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {

            $serie = new Serie();
            $serie
                ->setName($faker->word())
                ->setStatus($faker->randomElement(["returning", "ended", "canceled"]))
                ->setPoster("poster.png")
                ->setTmdbId($faker->randomDigit())
                ->setPopularity($faker->numberBetween(1, 1000))
                ->setVote($faker->numberBetween(0, 10))
                ->setFirstAirDate($faker->dateTimeBetween("- 2 year", "- 6 month"));
            $serie->setDateCreated(new \DateTime())
                ->setBackdrop("backdrop.png")
                ->setGenres($faker->randomElement(["Western", "SF", "Fantasy", "Drama"]))
                ->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirDate()));

            $manager->persist($serie);
        }

        $manager->flush();
    }

}
