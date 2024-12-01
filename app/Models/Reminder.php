<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'body',
        'reminder_date',
        'status',
        'repeat',
        'reminder_type_id',
        'reminder_group_id'
    ];

    public function reminderGroup(){
        return $this->belongsTo(ReminderGroups::class, 'reminder_group_id', 'id');
    }
}
