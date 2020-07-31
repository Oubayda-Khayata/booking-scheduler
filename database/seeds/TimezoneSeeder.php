<?php

use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Timezone::create([
            'name' => 'GMT+12',
            'utc_offset' => (12 * 60)
        ]);
        \App\Models\Timezone::create([
            'name' => 'GMT+3',
            'utc_offset' => (3 * 60)
        ]);
        \App\Models\Timezone::create([
            'name' => 'GMT+2',
            'utc_offset' => (2 * 60)
        ]);
    }
}
