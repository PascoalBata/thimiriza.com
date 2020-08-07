<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Empresa;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Empresa::class, function (Faker $faker) {
    return [
        //
        'empresa_id' => $faker->unique()->word,
        'empresa_nome' => $faker->word,
        'empresa_tipo' => "ISPC",
        'empresa_nuit' => $faker->unique()->word,
        'empresa_telefone' => $faker->word,
        'empresa_endereco' => $faker->word,
        'empresa_numero_conta' => $faker->word,
        'empresa_titular_conta' => $faker->name,
        'empresa_nib' => $faker->word,
        'empresa_nome_banco' =>$faker->word,
        'empresa_inscricao' => now(),
        'empresa_email' => $faker->unique()->safeEmail,
        'empresa_logo' => $faker->word,
        'empresa_estado' =>"OFF",
        'id_pacote' => 1,
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
    ];
});
