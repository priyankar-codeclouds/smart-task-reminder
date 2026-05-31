<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/reminders/check', [TaskController::class, 'checkReminders']);
