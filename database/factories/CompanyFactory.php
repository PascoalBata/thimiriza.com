<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->unique()->word,
        'name' => $this->faker->word,
        'type' => "ISPC",
        'nuit' => $this->faker->unique()->word,
        'phone' => $this->faker->word,
        'address' => $this->faker->word,
        'bank_account_number' => $this->faker->word,
        'bank_account_owner' => $this->faker->name,
        'bank_account_nib' => $this->faker->word,
        'bank_account_name' =>$this->faker->word,
        'email' => $this->faker->unique()->safeEmail,
        'logo' => $this->faker->word,
        'status' =>"OFF",
        'id_package' => 1,
        'email_verified_at' => now(),
        'remember_token' => Str::random(10),
        ];
    }
}
