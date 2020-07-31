<?php

use Illuminate\Database\Seeder;

class AppointmentDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AppointmentDuration::create([
            'name' => '15 minutes',
            'duration_in_minutes' => 15
        ]);
        \App\Models\AppointmentDuration::create([
            'name' => '30 minutes',
            'duration_in_minutes' => 30
        ]);
        \App\Models\AppointmentDuration::create([
            'name' => '45 minutes',
            'duration_in_minutes' => 45
        ]);
        \App\Models\AppointmentDuration::create([
            'name' => '1 hour',
            'duration_in_minutes' => 60
        ]);
    }
}
