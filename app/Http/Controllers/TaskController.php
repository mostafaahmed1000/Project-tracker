<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\TaskRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function store(TaskRequest $request)
    {  
        Task::create($request->all());
       return redirect()->back()->with('success', 'Task created.');
    }

    public function edit(Project $project, Task $task)
    {
        return view('tasks.edit', compact('project','task'));
    }

    public function update(TaskRequest $request, Project $project, Task $task)
    {

        $task->update($request->validated());

        return redirect()->route('projects.show', $project)->with('success', 'Task updated.');
    }

    public function toggleDone(Task $task)
    {

        $task->update(['is_done' => !$task->is_done]);

        return response()->json(['success' => true, 'is_done' => $task->is_done]);
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Task deleted.');
    }

    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();

        return redirect()->back()->with('success', 'Task restored.');
    }
}
