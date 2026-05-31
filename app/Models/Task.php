<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'reminder_sent',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'reminder_sent' => 'boolean',
    ];
}
