<?php

namespace App\Observers;
use App\Models\ProjectActivity;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class ProjectObserver
{
     protected function clearCache()
    {
        $keys = Cache::get('projects_cache_keys', []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Cache::forget('projects_cache_keys'); // reset key list
    }
    public function created(Project $project)
    {
        $this->clearCache();
        $this->log($project, 'created');
    }

    public function updated(Project $project)
    {
        $this->clearCache();
        $this->log($project, 'updated', $project->getChanges());
    }

    public function deleted(Project $project)
    {
        $this->clearCache();
        $this->log($project, 'deleted');
    }

    public function restored(Project $project)
    {
        $this->clearCache();
        $this->log($project, 'restored');
    }

    public function forceDeleted(Project $project)
    {
        $this->clearCache();
        $this->log($project, 'forceDeleted');
    }

    protected function log(Project $entity, string $action, array $payload = [])
    {
        ProjectActivity::create([
            'user_id'     => Auth::id() ?? 1,
            'entity_type' => get_class($entity),
            'entity_id'   => $entity->id,
            'action'      => $action,
            'payload'     => $payload ? json_encode($payload) : null,
        ]);
    }
}

