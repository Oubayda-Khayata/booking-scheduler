<?php

namespace App\Http\Controllers;

use App\Models\Expert;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function __construct()
    {
        $this->middleware('api_key_auth');
        $this->middleware('timezone');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expertsDb = Expert::with(['countryTimezone.country', 'countryTimezone.timezone'])->get();
        $experts = array();
        $userTimezone = CarbonTimeZone::create(request()->header('timezone'));
        foreach($expertsDb as $expertDb) {
            $expertTimezone = $expertDb['countryTimezone']['timezone']['name'];

            $fromDate = Carbon::create(0, 1, 1, 0, 0, 0, $expertTimezone);
            $expertWorkingTimeFrom = (int)$expertDb['daily_working_time_from'];
            $fromDate->addMinutes($expertWorkingTimeFrom);
            // Convert daily working time of the expert from his timezone to the user timezone
            $fromDate->setTimezone($userTimezone->toOffsetName());

            $toDate = Carbon::create(0, 1, 1, 0, 0, 0, $expertTimezone);
            $expertWorkingTimeTo = (int)$expertDb['daily_working_time_to'];
            // Check whether the expert works continuously from one day to the next day
            if ($expertWorkingTimeTo < $expertWorkingTimeFrom) {
                $expertWorkingTimeTo += 1440;
            }
            $toDate->addMinutes($expertWorkingTimeTo);
            // Convert daily working time of the expert from his timezone to the user timezone
            $toDate->setTimezone($userTimezone->toOffsetName());

            $experts[] = [
                'id' => $expertDb['id'],
                'firstname' => $expertDb['firstname'],
                'lastname' => $expertDb['lastname'],
                'gender' => $expertDb['gender'],
                'expertise' => $expertDb['expertise'],
                'country' => $expertDb['countryTimezone']['country']['name'],
                'daily_working_time_from' => $fromDate->isoFormat('hh:mm A'),
                'daily_working_time_to' => $toDate->isoFormat('hh:mm A')
            ];
        }
        return json('success', ['Experts retrieved successfully'], ['experts' => $experts]);
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
        $expertDb = Expert::where('id', $id);
        if(!$expertDb->exists()) {
            return json('error', ['expert id not found'], ['id' => $id], 404);
        }

        $userTimezone = CarbonTimeZone::create(request()->header('timezone'));
        $expertDb = $expertDb->with(['countryTimezone.country', 'countryTimezone.timezone'])->first();
        $expertTimezone = $expertDb['countryTimezone']['timezone']['name'];

        $fromDate = Carbon::create(0, 1, 1, 0, 0, 0, $expertTimezone);
        $expertWorkingTimeFrom = (int)$expertDb['daily_working_time_from'];
        $fromDate->addMinutes($expertWorkingTimeFrom);
        // Convert daily working time of the expert from his timezone to the user timezone
        $fromDate->setTimezone($userTimezone->toOffsetName());

        $toDate = Carbon::create(0, 1, 1, 0, 0, 0, $expertTimezone);
        $expertWorkingTimeTo = (int)$expertDb['daily_working_time_to'];
        // Check whether the expert works continuously from one day to the next day
        if ($expertWorkingTimeTo < $expertWorkingTimeFrom) {
            $expertWorkingTimeTo += 1440;
        }
        $toDate->addMinutes($expertWorkingTimeTo);
        // Convert daily working time of the expert from his timezone to the user timezone
        $toDate->setTimezone($userTimezone->toOffsetName());

        $expert = [
            'id' => $expertDb['id'],
            'firstname' => $expertDb['firstname'],
            'lastname' => $expertDb['lastname'],
            'gender' => $expertDb['gender'],
            'expertise' => $expertDb['expertise'],
            'country' => $expertDb['countryTimezone']['country']['name'],
            'daily_working_time_from' => $fromDate->isoFormat('hh:mm A'),
            'daily_working_time_to' => $toDate->isoFormat('hh:mm A')
        ];
        return json('success', ['Expert retrieved successfully'], ['expert' => $expert]);
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
