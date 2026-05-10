<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
    // GET /api/v1/tasks
    public function index(Request $request)
    {
        $tasks = $request->user()
                         ->tasks()
                         ->latest()
                         ->get();

        return TaskResource::collection($tasks);
    }

    // POST /api/v1/tasks
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,done',
            'due_date'    => 'nullable|date|after:today',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return new TaskResource($task);
    }

    // GET /api/v1/tasks/{task}
    public function show(Request $request, Task $task)
    {
        // Make sure user owns this task
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        return new TaskResource($task);
    }

    // PUT /api/v1/tasks/{task}
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:pending,in_progress,done',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return new TaskResource($task);
    }

    // DELETE /api/v1/tasks/{task}
    public function destroy(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted',
        ]);
    }
}
