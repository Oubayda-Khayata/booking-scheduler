<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expert extends Model
{
    use SoftDeletes;

    protected $table = 'experts';

    protected $fillable = [
        'country_timezone_id',
        'firstname',
        'lastname',
        'gender',
        'expertise',
        /**
         * working time measured in minutes: each day have 1440 minutes.
             * So, daily working time can have values from 0 -> 1439
             * In case an expert works from 10:00 P.M. to 03:00 A.M., the values would be:
             * from: 1320, to: 180
             * In case an expert works from 12:00 P.M. to 06:00 P.M., the values would be:
             * from: 720, to: 1080
             *
             * In case expert's working time is discrete (e.g. from 12:00 P.M. to 02:00 P.M. and from 04:00 P.M. to 06:00 P.M)
             * there are two solutions:
             * 1. create working_times table
             * 2. save working times as JSON string in experts table
             */
        'daily_working_time_from',
        'daily_working_time_to'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function appointments()
    {
        return $this->hasMany('App\Models\Appointment');
    }

    public function countryTimezone()
    {
        return $this->belongsTo('App\Models\CountryTimezone');
    }
}
