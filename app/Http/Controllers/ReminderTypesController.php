<?php

namespace App\Http\Controllers;

use App\Models\ReminderTypes;
use App\Http\Requests\StoreReminderTypesRequest;
use App\Http\Requests\UpdateReminderTypesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReminderTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user()->reminderTypes()->get();
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
    public function store(StoreReminderTypesRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $type = Auth::user()->reminderTypes()->create($validatedData);

            return response()->json($type, 201);
        } catch (\Exception $ex) {
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
