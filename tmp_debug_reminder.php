<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dbNow = Illuminate\Support\Facades\DB::selectOne('SELECT NOW() as n')->n;
$task = App\Models\Task::where('status', 'pending')
    ->orderBy('due_date')
    ->first(['id', 'title', 'due_date', 'status', 'reminder_sent']);

echo 'app_now=' . now()->toDateTimeString() . PHP_EOL;
echo 'app_tz=' . config('app.timezone') . PHP_EOL;
echo 'db_now=' . $dbNow . PHP_EOL;

if ($task) {
    echo 'task_id=' . $task->id . PHP_EOL;
    echo 'task_due_date=' . $task->due_date->toDateTimeString() . PHP_EOL;
    echo 'task_due_tz=' . $task->due_date->timezoneName . PHP_EOL;
    echo 'task_reminder_sent=' . ($task->reminder_sent ? 'true' : 'false') . PHP_EOL;
} else {
    echo 'no_pending_task' . PHP_EOL;
}
