@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="h3 mb-1">Smart Task Reminder Dashboard</h1>
        <p class="text-muted mb-2">Track tasks, reminders and productivity.</p>
        <p class="mb-0">
            You currently have {{ $totalTasks }} tasks, {{ $pendingTasks }} pending and
            <span class="fw-semibold text-danger">{{ $overdueTasks }} overdue</span>.
        </p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-primary h-100">
                <div class="card-body">
                    <p class="text-primary text-uppercase small mb-2">Total Tasks</p>
                    <h2 class="mb-0">{{ $totalTasks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-warning h-100">
                <div class="card-body">
                    <p class="text-warning text-uppercase small mb-2">Pending Tasks</p>
                    <h2 class="mb-0">{{ $pendingTasks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-success h-100">
                <div class="card-body">
                    <p class="text-success text-uppercase small mb-2">Completed Tasks</p>
                    <h2 class="mb-0">{{ $completedTasks }}</h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-danger h-100">
                <div class="card-body">
                    <p class="text-danger text-uppercase small mb-2">Overdue Tasks</p>
                    <h2 class="mb-0 text-danger">{{ $overdueTasks }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Quick Actions</div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add New Task</a>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">View All Tasks</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">Upcoming Tasks</div>
                <div class="card-body p-0">
                    @if ($upcomingTasks->isEmpty())
                        <p class="p-3 mb-0">No upcoming tasks.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingTasks as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>{{ $task->due_date->format('d M Y h:i A') }}</td>
                                            <td><span class="badge text-bg-warning">Pending</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header">Recently Completed Tasks</div>
                <div class="card-body p-0">
                    @if ($recentCompletedTasks->isEmpty())
                        <p class="p-3 mb-0">No completed tasks yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Completed Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentCompletedTasks as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td><span class="badge text-bg-success">Completed</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
