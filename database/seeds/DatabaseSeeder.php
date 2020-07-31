<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AppointmentDurationSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(TimezoneSeeder::class);
        $this->call(CountryTimezoneSeeder::class);
        $this->call(ExpertSeeder::class);
    }
}
