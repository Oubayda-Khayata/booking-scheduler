<?php

use Illuminate\Database\Seeder;

class ExpertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Expert::create([
            'country_timezone_id' => 282,
            'firstname' => 'William',
            'lastname' => 'Jordan',
            'gender' => 'male',
            'expertise' => 'Doctor',
            'daily_working_time_from' => 360, // 6 * 60
            'daily_working_time_to' => 1020 // 17 * 60
        ]);
        \App\Models\Expert::create([
            'country_timezone_id' => 355,
            'firstname' => 'Quasi',
            'lastname' => 'Shawa',
            'gender' => 'male',
            'expertise' => 'Civil engineer',
            'daily_working_time_from' => 360, // 6 * 60
            'daily_working_time_to' => 720 // 12 * 60
        ]);
        \App\Models\Expert::create([
            'country_timezone_id' => 145,
            'firstname' => 'Shimaa',
            'lastname' => 'Badawy',
            'gender' => 'female',
            'expertise' => 'Computer Engineer',
            'daily_working_time_from' => 780, // 13 * 60
            'daily_working_time_to' => 840 // 14 * 60
        ]);
    }
}
