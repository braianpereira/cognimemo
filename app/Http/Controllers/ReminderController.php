<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if($request->loadBy == 'today')
            $reminders = Auth::user()->reminders()->whereDate('reminder_date', now())->get();
        else if($request->loadBy == 'tomorrow')
            $reminders = Auth::user()->reminders()->whereDate('reminder_date', now()->addDay())->get();
        else if($request->loadBy == 'weekly')
            $reminders = Auth::user()->reminders()->whereBetween('reminder_date', [
                now()->addWeeks($request->skip)->startOfWeek()->startOfDay()->format('Y-m-d H:i:s'),
                now()->addWeeks($request->skip)->endOfWeek()->endOfDay()->format('Y-m-d H:i:s'),
            ])->get();
        else if($request->loadBy == 'monthly')
            $reminders = Auth::user()->reminders()->whereBetween('reminder_date', [
                now()->addMonths($request->skip)->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'),
                now()->addMonths($request->skip)->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'),
            ])->get();

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder)
    {
        //
    }
}
