<?php

namespace App\DataFixtures\Category;

use Faker\Factory;

use JetBrains\PhpStorm\NoReturn;

use App\DataFixtures\DataFixtures;
use Doctrine\Persistence\ObjectManager;

final class CategoryAttachmentFixtures extends DataFixtures
{
    #[NoReturn]
    public function load(ObjectManager $manager, $property = [], $n = 1): void
    {
        $faker = Factory::create();

        $property = [];

        $i = 1;

        $property = [
            'firstTitle' => $faker->realText(),
            'lastTitle' => $faker->realText(7000),
        ];

        parent::load($manager, $property, $n);
    }

    public function getOrder(): int
    {
        return 8;
    }
}