<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $table = 'countries';

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function countryTimezones() {
        return $this->hasMany('App\Models\CountryTimezone');
    }

    public function timezones() {
        return $this->belongsToMany('App\Models\Timezone', 'country_timezone', 'country_id', 'timezone_id');
    }
}
