<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Package;
use Faker\Generator as Faker;

$factory->define(Package::class, function (Faker $faker) {
    return [
        'name' => 'THIMIRIZA-OFF',
        'months' => 1,
        'users' => 5,
        'price' => 0,
        'created_at' => now(),
    ];
});
