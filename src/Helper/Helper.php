<?php

namespace App\Helper;

use Faker\Factory;

class Helper
{
    public static function gordyMockData(): array
    {
        $mockData = [];
        for ($i = 1; $i <= 30; $i++) {
            $cookTime = rand(1, 10);
            $name = self::getFakeName();
            $mockData[] = [
                'name' => $name,
                'cook_time' => $cookTime
            ];
        }

        return $mockData;
    }

    public static function getFakeName(): string
    {
        return Factory::create()->name;
    }

}