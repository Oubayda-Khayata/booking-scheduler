<?php

use Illuminate\Database\Seeder;

class CountryTimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CountryTimezone::create([
            'country_id' => 1,
            'timezone_id' => 1
        ]);
        \App\Models\CountryTimezone::create([
            'country_id' => 2,
            'timezone_id' => 2
        ]);
        \App\Models\CountryTimezone::create([
            'country_id' => 3,
            'timezone_id' => 3
        ]);
    }
}
