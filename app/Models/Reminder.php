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
        'starts_at',
        'ends_at',
        'reference'
    ];
}
