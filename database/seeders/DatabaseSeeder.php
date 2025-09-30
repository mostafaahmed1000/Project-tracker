<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $adminRole = Role::createOrFirst(['name' => 'admin']);
        $managerRole = Role::createOrFirst(['name' => 'manager']);
        $contributorRole = Role::createOrFirst(['name' => 'contributor']);
        
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ])->assignRole($adminRole);
        
        $manager = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
        ])->assignRole($managerRole);
        
        $contributor = User::factory()->create([
            'name' => 'Contributor User',
            'email' => 'contributor@example.com',
        ])->assignRole($contributorRole);
        
        Project::factory(3)
            ->has(Task::factory()->count(5)->state(function (array $attributes, Project $project) use ($contributor) {
                return ['project_id' => $project->id, 'assignee_id' => $contributor->id];
            }))
            ->create([
                'owner_id' => $manager->id,
            ]);
    }
}
