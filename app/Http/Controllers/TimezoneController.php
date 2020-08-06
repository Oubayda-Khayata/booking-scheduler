<?php

namespace App\Http\Controllers;

use App\Models\Timezone;
use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('api_key_auth');
    }

    public function getUserTimezone()
    {
        $ipApiUri = 'http://ip-api.com/json/' . request()->ip() . '?fields=timezone';
        $ipInfoResponse = file_get_contents($ipApiUri);
        $timezone = json_decode($ipInfoResponse, true)['timezone'];
        return json('success', ['Timezone retrieved successfully'], ['timezone' => $timezone]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json('success', ['timezones retrieved successfully'], ['timezones' => Timezone::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
