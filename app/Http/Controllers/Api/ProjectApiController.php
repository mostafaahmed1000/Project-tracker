<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class ProjectApiController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {   
        $projects = Project::with('owner')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->orderBy($request->get('sort', 'due_date'), 'asc')
            ->paginate(10);

        return ProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        $project = Project::create($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planned,active,paused,completed',
            'due_date' => 'nullable|date',
        ]) + ['owner_id' => $request->user()->id]);

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {   
        $this->authorize('view', $project);
        return new ProjectResource($project->load('tasks.attachments', 'owner'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        $project->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planned,active,paused,completed',
            'due_date' => 'nullable|date',
        ]));

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        
        $project = Project::withTrashed()->findOrFail($id);
        $this->authorize('restore', $project);

        $project->restore();
        return new ProjectResource($project);
    }


    public function tasks(Project $project)
    {
        $tasks = $project->tasks()->with('attachments')->get();
        return TaskResource::collection($tasks);
    }
}
