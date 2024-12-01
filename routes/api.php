<?php

use App\Http\Controllers\AdvisorReminderController;
use App\Http\Controllers\AdvisorReminderTypesController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReminderTypesController;
use App\Http\Controllers\UserAdvisorController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/reminders/types', ReminderTypesController::class);
    Route::apiResource('/reminders', ReminderController::class);
    Route::put('/reminders/{reminder}/toggle-status', [ReminderController::class, 'toggleStatus']);
    Route::delete('/reminders/{reminder}/group/{groupStatus}', [ReminderController::class, 'destroy']);
    Route::put('/reminders/{reminder}/to/{action?}', [ReminderController::class, 'update']);
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/advisor', UserAdvisorController::class);
    Route::prefix('advisor/user/{user}')->group(function () {
        Route::apiResource('/', UserAdvisorController::class);
        Route::apiResource('/reminders/types', AdvisorReminderTypesController::class);
        Route::apiResource('/reminders', AdvisorReminderController::class);
        Route::put('/reminders/{reminder}/toggle-status', [AdvisorReminderController::class, 'toggleStatus']);
        Route::delete('/reminders/{reminder}/group/{groupStatus}', [AdvisorReminderController::class, 'destroy']);
        Route::put('/reminders/{reminder}/to/{action?}', [AdvisorReminderController::class, 'update']);
    });
});
