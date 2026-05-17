<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\IndexTaskRequest;
use App\Http\Requests\Task\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(IndexTaskRequest $request)
    {
        $validated = $request->validated();

        $perPage = $validated['per_page'] ?? 5;
        $search = $validated['search'] ?? null;
        $status = $validated['status'] ?? null;
        $priority = $validated['priority'] ?? null;
        $sortBy = $validated['sort_by'] ?? 'created_at';
        $sortOrder = $validated['sort_order'] ?? 'desc';

        $tasks = $request->user()
            ->tasks()
            ->search($search)
            ->filterByStatus($status)
            ->filterByPriority($priority)
            ->sortBy($sortBy, $sortOrder)
            ->paginate($perPage);

        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);
        
        $validated = $request->validated();

        $task = $request->user()->tasks()->create($validated);

        return new TaskResource($task);
    }

    public function show(Request $request, Task $task)
    {

        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validated();

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy( Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted',
        ]);
    }
}