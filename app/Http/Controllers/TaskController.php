<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = $request->user()
            ->tasks()
            ->latest()
            ->get();
    
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
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

    public function destroy(Request $request, Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted',
        ]);
    }
}
