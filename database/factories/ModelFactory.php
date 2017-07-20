<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

// 生成消费记录
$factory->define(App\Models\Consume::class, function (Faker\Generator $faker) {
    return [
        'mark' => $faker->realText(20),
        'type' => rand(0,1),
        'user_id' => rand(0,600),
        'order_id' => rand(0,100),
        'price' => $faker->randomFloat(2,10,1000),
        'created_at' => $faker->dateTimeBetween($startDate = '-5 days', $endDate = 'now'),
    ];
});
