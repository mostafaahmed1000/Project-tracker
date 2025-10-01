<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Http\Requests\TaskRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function store(TaskRequest $request)
    {  
        Task::create($request->all());
       return redirect()->back()->with('success', 'Task created.');
    }

    public function edit(Task $task)
    {
        $project = $task->project;
        $contributors = User::role('contributor')->get();

        return view('tasks.edit', compact('project','task', 'contributors'));
    }

    public function update(TaskRequest $request, Task $task)
    {

        $task->update($request->validated());

        $project = $task->project;
        return redirect()->route('projects.show', $project)->with('success', 'Task updated.');
    }

    public function toggleDone(Task $task)
    {

        $task->update(['is_done' => !$task->is_done]);

        return response()->json(['success' => true, 'is_done' => $task->is_done]);
    }

    public function destroy(Task $task)
    {
        $project = $task->project;
        $task->delete();
        
        return redirect()->route('projects.show', $project)->with('success', 'Task deleted.');
    }

    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();

        return redirect()->back()->with('success', 'Task restored.');
    }

    public function forceDelete($id)
    {
        $task = Task::withTrashed()->findOrFail($id);

        DB::transaction(function () use ($task) {
        
            foreach ($task->attachments as $attachment) {
                \Storage::delete($attachment->file_path);
            }
            $task->forceDelete();
        });

        return redirect()->back()->with('success', 'Task permanently deleted.');
    }
}
