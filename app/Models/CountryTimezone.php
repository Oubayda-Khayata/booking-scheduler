<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryTimezone extends Model
{
    use SoftDeletes;

    protected $table = 'country_timezone';

    protected $fillable = [
        'country_id',
        'timezone_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

    public function timezone() {
        return $this->belongsTo('App\Models\Timezone');
    }
}
