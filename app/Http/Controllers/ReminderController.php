<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReminderCollection;
use App\Models\Reminder;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Models\ReminderGroups;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $label = "";

        switch ($request->section){
            case 'today':
                $day = now()->addDays((int)$request->index);

                switch ($request->index){
                    case '-1': $label = 'Ontem'; break;
                    case '0': $label = 'Hoje'; break;
                    case '1': $label = 'Amanhã'; break;
                    default: $label = $day->week == now()->week ? $day->dayName : $day->format('d/m/Y');
                }

                $data = new ReminderCollection(Auth::user()->reminders()->whereDate('reminder_date', $day)->get());
                break;
            case 'week':
                $week = now()->addWeeks((int)$request->week);
                switch ($request->index) {
                    case '-1': $label = 'Passada'; break;
                    case '0': $label = 'Nesta'; break;
                    case '1': $label = 'Próxima'; break;
                    default: $label = $week->startOfWeek()->format('d/m/Y') . ' - ' . $week->endOfWeek()->format('d/m/Y');
                }

                $data = new ReminderCollection(Auth::user()->reminders()->whereBetween('reminder_date', [
                    $week->startOfWeek()->startOfDay()->format('Y-m-d H:i:s'),
                    $week->endOfWeek()->endOfDay()->format('Y-m-d H:i:s'),
                ])->orderBy('reminder_date')->get());

                break;
            case 'month':
                $month = now()->addMonths((int)$request->index);
                $label = ucfirst($month->shortMonthName) . $month->format('/Y');

                $data = new ReminderCollection(Auth::user()->reminders()->whereBetween('reminder_date', [
                    $month->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'),
                    $month->endOfMonth()->endOfDay()->format('Y-m-d H:i:s'),
                ])->orderBy('reminder_date')->get());

                break;
        }

        $reminders['label'] = ucfirst($label);
        $reminders['data'] = $data;

        return response()->json($reminders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReminderRequest $request)
    {
        try {
            $reminder = $request->all();
            $reminder['body'] = '';

            DB::beginTransaction();

            if($reminder['repeat']) {
                $group = ReminderGroups::create([
                    'period' => $reminder['period'],
                    'starts_at' => $reminder['reminder_date'],
                    'ends_at' => $reminder['ends_at'],
                ]);

                $reminder['reminder_group_id'] = $group->id;

                $reminders  = [$reminder];

                $start = Carbon::parse($reminder['reminder_date']);
                $end = Carbon::parse($reminder['ends_at']);

                while ($start->lt($end)) {
                    switch ($reminder['period']) {
                        case 'daily':
                            $start->addDay();
                            break;
                        case 'weekly':
                            $start->addWeek();
                            break;
                        case 'monthly':
                            $start->addMonth();
                            break;
                        case 'yearly':
                            $start->addYear();
                            break;
                    }

                    $reminders[] = [
                        ...$request->all(),
                        'body' => 'body',
                        'reminder_date' => $start->format('Y-m-d H:i:s'),
                        'reminder_group_id' => $group->id
                    ];
                }

                $reminder = (Auth::user()->reminders()->createMany($reminders))[0];
            } else {
                $reminder = Auth::user()->reminders()->create($reminder);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($reminder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreReminderRequest $request, Reminder $reminder, $action = null)
    {
        if($reminder->repeat == true) {
            if($request->get('repeat') == false) {
                $reminder->reminderGroup()->reminders->whereNot('id', $reminder->id)->delete();
            }
            else if($action == 'all') {
                if($reminder->reminderGroup && $reminder->reminderGroup->reminders && $reminder->reminderGroup->reminders()->delete()){
                    if($reminder->reminderGroup) {
                        $reminder->reminderGroup()->delete();
                    }
                }
            }
            return $this->store($request);
        } else {
            if($request->get('repeat') == true) {
               $reminder->delete();
                return $this->store($request);
            }
        }

        $reminder->update($request->all());

        return response()->json($reminder);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reminder $reminder, $groupStatus)
    {
        $status = false;

        switch ($groupStatus) {
            case 'maintain':  $status = $reminder->delete(); break;
            case 'removeAll':
                try {
                    DB::beginTransaction();

                    if($reminder->reminderGroup && $reminder->reminderGroup->reminders()->delete()){
                        $status = $reminder->reminderGroup()->delete();
                    } else {
                        $status = $reminder->delete();
                    }

                    DB::commit();
                } catch (\Exception $exception) {
                    $status = false;
                    Log::error($exception);
                    DB::rollBack();
                }


                break;
            default: $status = $reminder->delete();
//            case 'removeForward': break;
        }
        return response()->json($status);
    }

    public function toggleStatus(Request $request, Reminder $reminder)
    {
        try {
            $reminder = Auth::user()->reminders()->where('id', $reminder->id)->firstOrFail();

            try {
                $reminder->status = $request->status;
                $reminder->save();
            } catch (\Exception $exception) {
                Log::error($exception);
                return response()->json(['error' => $exception->getMessage()], 500);
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error($e);
            return response()->json(['error' => 'reminder not found'], 404);
        }

        return response()->json($reminder);
    }
}
