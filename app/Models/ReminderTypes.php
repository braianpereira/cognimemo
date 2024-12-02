<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderTypes extends Model
{
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
