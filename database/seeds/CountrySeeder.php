<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Country::create([
            'name' => 'Anabar'
        ]);
        \App\Models\Country::create([
            'name' => 'Syria'
        ]);
        \App\Models\Country::create([
            'name' => 'Egypt'
        ]);
    }
}
