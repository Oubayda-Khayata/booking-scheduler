<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $table = 'appointments';

    protected $fillable = [
        'username',
        'appointment_duration_id',
        'expert_id',
        'datetime' // UTC UNIX
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function expert() {
        return $this->belongsTo('App\Models\Expert');
    }

    public function appointmentDuration() {
        return $this->belongsTo('App\Models\AppointmentDuration');
    }
}
