<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\RetrieveExpertAppointmentsRequest;
use App\Http\Requests\RetrieveTimeSlotsRequest;
use App\Models\Appointment;
use App\Models\AppointmentDuration;
use App\Models\Expert;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use DateTimeZone;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('api_key_auth');
        $this->middleware('timezone');
    }

    public function getAppointmentDurations()
    {
        return json('success', ['Appointment Durations retrieved successfully'], ['appointment_durations' => AppointmentDuration::all()]);
    }

    // Check if the datetime is available
    public function bookAppointment(CreateAppointmentRequest $request)
    {
        $expertId = $request->input('expert_id');
        $appointmentDurationId = $request->input('appointment_duration_id');
        $expert = Expert::where('id', $expertId);
        $appointmentDuration = AppointmentDuration::where('id', $appointmentDurationId);
        if (!$expert->exists()) {
            return json('error', ['expert id not found'], ['id' => $expertId], 404);
        }
        if (!$appointmentDuration->exists()) {
            return json('error', ['appointment duration id not found'], ['id' => $appointmentDurationId], 404);
        }
        $appointmentDuration = $appointmentDuration->first();


        $userTimezone = CarbonTimeZone::create($request->header('timezone'));
        $appointmentStartDateTime = Carbon::createFromTimestamp((int)$request->input('datetime'), $userTimezone->toOffsetName());
        $appointmentStartDateTime->utc();

        /* Get all appointments of the selected expert
        *  in the user selected date and time to check
        *  whether there is an intersection with another appointment
        */
        $appointments = Appointment::where('expert_id', $expertId)
            ->with(['appointmentDuration'])
            ->get()
            ->toArray();

        $doesIntersectWithAppointment =
            // Get appointments count that intersects with the selected date and time
            count(array_filter($appointments, function ($appointment) use ($appointmentStartDateTime, $appointmentDuration) {
                $startDateTime = $appointmentStartDateTime->clone();
                $endDateTime = $appointmentStartDateTime->clone()->addMinutes($appointmentDuration['duration_in_minutes']);
                $appointmentStartDateTime = Carbon::createFromTimestamp($appointment['datetime']);
                $appointmentEndDateTime = $appointmentStartDateTime->clone()->addMinutes($appointment['appointment_duration']['duration_in_minutes']);
                return !(
                    (
                        // If the start and the end of the current time slot is less than the current appointment date and time
                        $startDateTime->getTimestamp() < $appointmentStartDateTime->getTimestamp() &&
                        $endDateTime->getTimestamp() <= $appointmentStartDateTime->getTimestamp()) || // Or
                    (
                        // If the start and the end of the current time slot is greater than the current appointment end date and time
                        $startDateTime->getTimestamp() >= $appointmentEndDateTime->getTimestamp() &&
                        $endDateTime->getTimestamp() > $appointmentEndDateTime->getTimestamp()));
            })) > 0;
        if ($doesIntersectWithAppointment) {
            return json('error', ['This appointment date and time is already booked'], [], 400);
        }

        $newAppointment = Appointment::create([
            'username' => $request->input('username'),
            'appointment_duration_id' => $appointmentDurationId,
            'expert_id' => $expertId,
            'datetime' => $appointmentStartDateTime->getTimestamp()
        ]);
        return json('success', ['Appointment created successfully'], ['appointment' => $newAppointment], 201);
    }

    public function getExpertAppointments(RetrieveExpertAppointmentsRequest $request, $expert_id)
    {
        $userTimezone = CarbonTimeZone::create($request->header('timezone'));
        $fromDate = Carbon::createFromTimestamp((int)$request->input('from_date'), $userTimezone->toOffsetName());
        $toDate = Carbon::createFromTimestamp((int)$request->input('to_date'), $userTimezone->toOffsetName());
        $fromDate->utc();
        $toDate->utc();

        $expertAppointmentsDb = Appointment::where('expert_id', $expert_id)
            ->where('datetime', '>=', $fromDate->getTimestamp())
            ->where('datetime', '<=', $toDate->getTimestamp())
            ->with(['appointmentDuration'])
            ->get();
        $expertAppointments = array();
        foreach ($expertAppointmentsDb as $expertAppointmentDb) {
            $appointmentDatetime = Carbon::createFromTimestamp((int)$expertAppointmentDb['datetime']);
            // $duration = $expertAppointmentDb['appointmentDuration']['duration_in_minutes'];
            // $appointmentDatetime->addMinutes($duration);
            $appointmentDatetime->setTimezone($userTimezone->toOffsetName());
            $expertAppointments[] = [
                // 'username' => $expertAppointmentDb['username'], // Do not send username to other users because they don't have the permissions to see others' name
                'datetime' => $appointmentDatetime->getTimestamp()
            ];
        }

        return json('success', ['Expert appointments retrieved successfully'], ['appointments' => $expertAppointments]);
    }

    public function getTimeSlots(RetrieveTimeSlotsRequest $request)
    {
        $expertId = $request->input('expert_id');
        $appointmentDurationId = $request->input('appointment_duration_id');
        $expert = Expert::where('id', $expertId)->with(['countryTimezone.timezone']);
        $appointmentDuration = AppointmentDuration::where('id', $appointmentDurationId);
        if (!$expert->exists()) {
            return json('error', ['expert id not found'], ['id' => $expertId], 404);
        }
        if (!$appointmentDuration->exists()) {
            return json('error', ['appointment duration id not found'], ['id' => $appointmentDurationId], 404);
        }
        $expert = $expert->first();
        $appointmentDuration = $appointmentDuration->first();

        $userTimezone = CarbonTimeZone::create($request->header('timezone'));
        $expertTimezone = CarbonTimeZone::create($expert['countryTimezone']['timezone']['name']);

        $userSelectedDate = Carbon::createFromTimestamp((int)$request->input('date'), $userTimezone->toOffsetName());
        $userSelectedDate->utc();


        $expertWorkingTimeFrom = Carbon::create(0, 1, 1, 0, 0, 0, $expertTimezone->toOffsetName());
        $expertWorkingTimeFrom->addMinutes((int)$expert['daily_working_time_from']);
        $expertWorkingTimeFrom->utc();
        // User selected date and expert working time all in UTC
        $expertFromDate = $userSelectedDate->clone()
            ->setTime(
                $expertWorkingTimeFrom->hour,
                $expertWorkingTimeFrom->minute,
                $expertWorkingTimeFrom->second // Not necessary, but to be accurate
            );

        $expertWorkingTimeTo = Carbon::create(0, 1, 1, 0, 0, 0, $expertTimezone->toOffsetName());
        $expertWorkingTimeTo->addMinutes((int)$expert['daily_working_time_to']);
        $expertWorkingTimeTo->utc();
        // User selected date and expert working time all in UTC
        $expertToDate = $userSelectedDate->clone()
            // In case the expert works continuously from one day to another
            ->addDays((int)$expert['daily_working_time_from'] > (int)$expert['daily_working_time_to'] ? 1 : 0)
            ->setTime(
                $expertWorkingTimeTo->hour,
                $expertWorkingTimeTo->minute,
                $expertWorkingTimeTo->second // Not necessary, but to be accurate
            );


        /* Get all appointments of the selected expert
        *  in the user selected date
        *  and within the working hours of the expert
        */
        $appointments = Appointment::where('expert_id', $expertId)
            ->where('datetime', '>=', $expertFromDate->getTimestamp())
            ->where('datetime', '<=', $expertToDate->getTimestamp())
            ->with('appointmentDuration')
            ->get()
            ->toArray();

        $minAppointmentDuration = AppointmentDuration::min('duration_in_minutes');

        $timeSlots = array();
        $currentDatetime = $expertFromDate->clone();
        while (true) {
            $isTimeSlotIntersectsWithAppointment =
                // Get appointments count that intersects with the current time slot
                count(array_filter($appointments, function ($appointment) use ($currentDatetime, $appointmentDuration) {
                    $candidateStartDateTime = $currentDatetime->clone();
                    $candidateEndDateTime = $currentDatetime->clone()->addMinutes($appointmentDuration['duration_in_minutes']);
                    $appointmentStartDateTime = Carbon::createFromTimestamp($appointment['datetime']);
                    $appointmentEndDateTime = $appointmentStartDateTime->clone()->addMinutes($appointment['appointment_duration']['duration_in_minutes']);
                    return !(
                        (
                            // If the start and the end of the current time slot is less than the current appointment date and time
                            $candidateStartDateTime->getTimestamp() < $appointmentStartDateTime->getTimestamp() &&
                            $candidateEndDateTime->getTimestamp() <= $appointmentStartDateTime->getTimestamp()) || // Or
                        (
                            // If the start and the end of the current time slot is greater than the current appointment end date and time
                            $candidateStartDateTime->getTimestamp() >= $appointmentEndDateTime->getTimestamp() &&
                            $candidateEndDateTime->getTimestamp() > $appointmentEndDateTime->getTimestamp()));
                })) > 0;

            $isTimeSlotWithinExpertAvailableTime =
                ($currentDatetime->clone()->addMinutes($appointmentDuration['duration_in_minutes'])->getTimestamp() <= $expertToDate->getTimestamp());

            if (
                !$isTimeSlotIntersectsWithAppointment &&
                $isTimeSlotWithinExpertAvailableTime
            ) {
                $timeSlots[] = [
                    'datetime' => $currentDatetime->clone()->setTimezone($userTimezone->toOffsetName())->getTimestamp(),
                    'from_time' => $currentDatetime->clone()->setTimezone($userTimezone->toOffsetName())->isoFormat('hh:mm A'),
                    'to_time' => $currentDatetime->clone()->addMinutes($appointmentDuration['duration_in_minutes'])->setTimezone($userTimezone->toOffsetName())->isoFormat('hh:mm A')
                ];
            }

            $currentDatetime->addMinutes($minAppointmentDuration);
            if ($currentDatetime->getTimestamp() > $expertToDate->getTimestamp()) {
                break;
            }
        }

        return json('success', ['Time slots retrieved successfully'], ['time_slots' => $timeSlots]);
    }
}
