<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
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
        $filter = $request->only(['status', 'search', 'trashed', 'sort', 'page']);
        $cacheKey = 'projects-' . md5(json_encode($filter));

        $keys = Cache::get('projects_cache_keys', []);
        if (!in_array($cacheKey, $keys)) {
            $keys[] = $cacheKey;
            Cache::forever('projects_cache_keys', $keys);
        }
        $projects = Cache::remember($cacheKey, 30, function () use ($request) {
            $query = Project::with('owner');

            if ($request->get('trashed')) {
                $query->onlyTrashed();
            }

            $query->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                ->when($request->sort, fn($q) => $q->orderBy("due_date", $request->sort));

            // Sorting doesn't work well with `orderBy` on trashed items if column is ambiguous.
            // For now, we sort by latest for trashed.

            return $query->latest()->paginate(10);
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
            $project = Project::create($request->validated() + ['owner_id' => auth()->id()]);

            if ($request->has('tasks')) {
                foreach ($request->tasks as $taskData) {
                    $project->tasks()->create([
                        'title' => $taskData['title'],
                        'priority' => $taskData['priority'] ?? 'medium',
                    ]);
                }
            }
        });

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load(['tasks.assignee', 'tasks.attachments',]);

        $trashedTasks = Task::onlyTrashed()
            ->where('project_id', $project->id)
            ->get();

        $contributors = User::role('contributor')->get();

        return view('projects.show', compact('project', 'contributors', 'trashedTasks'));
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
        $project->restore();

        return redirect()->route('projects.index')->with('success', 'Project restored.');
    }

    public function forceDelete($id)
    {
        $project = Project::withTrashed()->findOrFail($id);

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
