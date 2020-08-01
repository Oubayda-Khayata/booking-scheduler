<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('api_key_auth');
    }

    public function getTimezone()
    {
        $ipApiUri = 'http://ip-api.com/json/' . request()->ip() . '?fields=offset';
        $ipInfoResponse = file_get_contents($ipApiUri);
        $timezone = json_decode($ipInfoResponse, true)['offset'];
        return json('success', ['Timezone retrieved successfully'], ['timezone' => $timezone]);
    }
}
