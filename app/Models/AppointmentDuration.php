<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentDuration extends Model
{
    use SoftDeletes;

    protected $table = 'appointment_durations';

    protected $fillable = [
        'name',
        'duration_in_minutes'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function appointments() {
        return $this->hasMany('App\Models\Appointment');
    }
}
