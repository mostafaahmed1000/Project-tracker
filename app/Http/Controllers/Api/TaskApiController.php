<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $task = $project->tasks()->create($request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]));

        return new TaskResource($task);
    }

    public function show(Task $task)
    {
        return new TaskResource($task->load('attachments','assignee'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->validate([
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]));

        return new TaskResource($task);
    }

    public function toggleDone(Task $task)
    {
        $task->update(['is_done' => !$task->is_done]);
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        return new TaskResource($task);
    }
}
