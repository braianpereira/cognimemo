<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'period', 'starts_at', 'ends_at'
    ];

    public function reminders()
    {
        return $this->hasMany(Reminder::class, 'reminder_group_id', 'id');
    }
}
