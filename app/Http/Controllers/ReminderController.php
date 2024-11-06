<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use Carbon\Traits\Localization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $day = now()->addDays((int)$request->today);

        switch ($request->today){
            case '-1': $dayLabel = 'Ontem'; break;
            case '0': $dayLabel = 'Hoje'; break;
            case '1': $dayLabel = 'Amanhã'; break;
            default: $dayLabel = $day->week == now()->week ? $day->dayName : $day->format('d/m/Y');
        }

        $week = now()->addWeeks((int)$request->week);
        switch ($request->week) {
            case '-1': $weekLabel = 'Passada'; break;
            case '0': $weekLabel = 'Nesta'; break;
            case '1': $weekLabel = 'Próxima'; break;
            default: $weekLabel = $week->startOfWeek()->format('d/m/Y') . ' - ' . $week->endOfWeek()->format('d/m/Y');
        }
        $month = now()->addMonths((int)$request->month);
        $monthLabel = ucfirst($month->shortMonthName) . $month->format('/Y');

        $reminders['today']['data'] = Auth::user()->reminders()->whereDate('reminder_date', $day)->get();
        $reminders['today']['label'] = ucfirst($dayLabel);
        $reminders['week']['data'] = Auth::user()->reminders()->whereBetween('reminder_date', [
            $week->startOfWeek()->startOfDay()->format('Y-m-d H:i:s'),
            $week->endOfWeek()->endOfDay()->format('Y-m-d H:i:s'),
        ])->get();
        $reminders['week']['label'] = ucfirst($weekLabel);
        $reminders['month']['data'] = Auth::user()->reminders()->whereBetween('reminder_date', [
            $month->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'),
            $month->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'),
        ])->get();
        $reminders['month']['label'] = $monthLabel;

        return response()->json($reminders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReminderRequest $request)
    {
        $reminder = $request->all();
        $reminder['body'] = '';
        $reminder = Auth::user()->reminders()->create($reminder);

        return response()->json($reminder);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reminder $reminder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reminder $reminder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        $reminder->update($request->all());

        return response()->json($reminder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder)
    {
        //
    }
}
