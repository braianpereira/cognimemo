<?php

use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReminderTypesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/reminders/types', ReminderTypesController::class);
Route::apiResource('/reminders', ReminderController::class);
