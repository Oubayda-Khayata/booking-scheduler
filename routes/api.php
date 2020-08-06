<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('experts', 'ExpertController');
Route::get('get-appointment-durations', 'BookingController@getAppointmentDurations');
Route::post('book-appointment', 'BookingController@bookAppointment');
Route::get('get-expert-appointments/{expert_id}', 'BookingController@getExpertAppointments');
Route::get('get-time-slots', 'BookingController@getTimeSlots');
Route::post('clear-appointments', 'BookingController@clearAppointments');
Route::get('get-timezone', 'TimezoneController@getUserTimezone');
Route::resource('timezones', 'TimezoneController');
