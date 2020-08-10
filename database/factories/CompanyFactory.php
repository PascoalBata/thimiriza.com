<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Company::class, function (Faker $faker) {
    return [
        //
        'id' => $faker->unique()->word,
        'name' => $faker->word,
        'type' => "ISPC",
        'nuit' => $faker->unique()->word,
        'phone' => $faker->word,
        'address' => $faker->word,
        'bank_account_number' => $faker->word,
        'bank_account_owner' => $faker->name,
        'bank_account_nib' => $faker->word,
        'bank_account_name' =>$faker->word,
        'email' => $faker->unique()->safeEmail,
        'logo' => $faker->word,
        'status' =>"OFF",
        'id_package' => 1,
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
    ];
});
