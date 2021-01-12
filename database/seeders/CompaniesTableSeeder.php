<?php

namespace Database\Seeders;

use App\Models\Companies;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(Company::class, 5)->create();
    }
}
