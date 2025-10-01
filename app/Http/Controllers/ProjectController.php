<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\ProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['status', 'search']);
        $cacheKey = 'projects-' . md5(json_encode($filter));

        $projects = Cache::remember($cacheKey, 30, function () use ($request) {
            $query = Project::with('owner')
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                ->orderBy($request->get('sort', 'due_date'));

            return $query->paginate(10);
        });

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
       
        return view('projects.create');
    }

    public function store(ProjectRequest $request)
    {
      
        DB::transaction(function () use ($request) {
            $project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'due_date' => $request->due_date,
                'owner_id' => Auth::id(),
            ]);
        });

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load(['tasks.assignee', 'tasks.attachments',]);

        $contributors = User::role('contributor')->get();

        return view('projects.show', compact('project', 'contributors'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project moved to trash.');
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $this->authorize('restore', $project);
        $project->restore();

        return redirect()->route('projects.index')->with('success', 'Project restored.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $project);

        DB::transaction(function () use ($project) {
            foreach ($project->tasks as $task) {
                foreach ($task->attachments as $attachment) {
                    \Storage::delete($attachment->file_path);
                }
            }
            $project->forceDelete();
        });

        return redirect()->route('projects.index')->with('success', 'Project permanently deleted.');
    }
}
