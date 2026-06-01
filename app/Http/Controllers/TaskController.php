<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function dashboard()
    {
        $now = now();

        $totalTasks = Task::count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $completedTasks = Task::where('status', 'completed')->count();
        $overdueTasks = Task::where('status', 'pending')
            ->where('due_date', '<', $now)
            ->count();

        $upcomingTasks = Task::where('status', 'pending')
            ->where('due_date', '>=', $now)
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        $recentCompletedTasks = Task::where('status', 'completed')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'overdueTasks',
            'upcomingTasks',
            'recentCompletedTasks'
        ));
    }

    private function validatedTaskData(Request $request): array
    {
        return $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date|after:now',
            ],
            [
                'due_date.after' => 'Due date must be a future date and time.',
            ]
        );
    }

    public function index()
    {
        $tasks = Task::orderBy('due_date', 'asc')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatedTaskData($request);

        Task::create([
            ...$validated,
            'status' => 'pending',
            'reminder_sent' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $this->validatedTaskData($request);

        $task->update([
            ...$validated,
            'reminder_sent' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function toggleStatus(Task $task)
    {
        $isPending = $task->status === 'pending';

        $task->status = $isPending ? 'completed' : 'pending';
        if (! $isPending) {
            $task->reminder_sent = false;
        }

        $task->save();

        $message = $isPending ? 'Task marked as completed.' : 'Task reverted to pending.';

        return redirect()->route('tasks.index')->with('success', $message);
    }

    public function checkReminders()
    {
        $now = now();
        $windowEnd = $now->copy()->addHours(12);

        $tasks = Task::query()
            ->where('status', 'pending')
            ->where('reminder_sent', false)
            ->where('due_date', '>=', $now)
            ->where('due_date', '<=', $windowEnd)
            ->orderBy('due_date', 'asc')
            ->get(['id', 'title', 'description', 'due_date']);

        if ($tasks->isNotEmpty()) {
            Task::whereIn('id', $tasks->pluck('id'))->update(['reminder_sent' => true]);
        }

        $tasks = $tasks->map(function ($task) use ($now) {
            $dueDateInAppTimezone = $task->due_date->copy()->timezone(config('app.timezone'));

            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date,
                'formatted_due_date' => $dueDateInAppTimezone->format('d M Y h:i A'),
                'hours_until_due' => round($now->diffInMinutes($task->due_date, false) / 60, 1),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'count' => $tasks->count(),
            'tasks' => $tasks,
        ]);
    }

    public function testTelegramConfig()
    {
        $botToken = config('telegram.bot_token');
        $chatId = config('telegram.chat_id');

        return response()->json([
            'configured' => filled($botToken) && filled($chatId),
            'bot_token_present' => filled($botToken),
            'chat_id_present' => filled($chatId),
        ]);
    }

    public function sendTelegramTest(TelegramService $telegramService)
    {
        $message = "🔔 Smart Task Reminder\n\nTelegram integration is working successfully.";
        $response = $telegramService->sendMessage($message);

        if (is_array($response)) {
            return response()->json($response, 422);
        }

        return response()->json($response->json(), $response->status());
    }
}
