@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-3">Create Task</h1>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input
                                type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                required
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="4"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date &amp; Time</label>
                            <input
                                type="datetime-local"
                                class="form-control @error('due_date') is-invalid @enderror"
                                id="due_date"
                                name="due_date"
                                value="{{ old('due_date') }}"
                                required
                            >
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Task</button>
                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

