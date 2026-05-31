@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Smart Task Reminder</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add New Task</a>
    </div>

    <div class="card">
        <div class="card-header">
            Task List
        </div>
        <div class="card-body p-0">
            @if ($tasks->isEmpty())
                <p class="p-3 mb-0">No tasks found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->description ?: '-' }}</td>
                                    <td>{{ $task->due_date->format('d M Y h:i A') }}</td>
                                    <td>
                                        @if ($task->status === 'pending')
                                            <span class="badge text-bg-warning">Pending</span>
                                        @else
                                            <span class="badge text-bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">Edit</a>

                                            <form action="{{ route('tasks.toggle-status', $task) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                @if ($task->status === 'pending')
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Complete</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-warning">Reopen</button>
                                                @endif
                                            </form>

                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

