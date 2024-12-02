<?php

namespace App\Http\Controllers;

use App\Models\ReminderTypes;
use App\Http\Requests\StoreReminderTypesRequest;
use App\Http\Requests\UpdateReminderTypesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdvisorReminderTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user)
    {
        try {
            $types = Auth::user()->advisees()->where('user_id', $user)->firstOrFail()->reminderTypes()->get();

            return response()->json($types);

        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReminderTypesRequest $request, $user)
    {
        try {
            $type = Auth::user()
                ->advisees()
                ->where('user_id', $user)
                ->firstOrFail()
                ->reminderTypes()
                ->create($request->all());

            return response()->json($type, 201);
        } catch (\Exception $ex) {
            Log::error($ex);
            return response()->json($ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReminderTypes $remindersType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReminderTypes $remindersType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReminderTypesRequest $request, ReminderTypes $remindersType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReminderTypes $remindersType)
    {
        //
    }
}
