<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pacote;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        //
        'pacote_nome' => 'INICIAL FREE',
        'pacote_meses' => 1,
        'pacote_utilizadores' => 5,
        'pacote_preco' => 0,
    ];
});
