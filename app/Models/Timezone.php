<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timezone extends Model
{
    use SoftDeletes;

    protected $table = 'timezones';

    protected $fillable = [
        'name',
        'utc_offset' // in minutes
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function countryTimezones() {
        return $this->hasMany('App\Models\CountryTimezone');
    }

    public function countries() {
        return $this->belongsToMany('App\Models\Country', 'country_timezone', 'timezone_id', 'country_id');
    }
}
