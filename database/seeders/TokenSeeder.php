<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TokenSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'manager', 'contributor'];

        foreach ($roles as $role) {
            // create user if not exists
            $user = User::firstOrCreate(
                ['email' => $role.'@example.com'],
                [
                    'name' => ucfirst($role),
                    'password' => bcrypt('password'), // default password
                ]
            );

            // assign role if you use Spatie or similar
            if (method_exists($user, 'assignRole')) {
                $user->assignRole($role);
            }

            // remove old tokens
            $user->tokens()->delete();

            // create a new token with role-based ability
            $token = $user->createToken($role.'-token', [$role])->plainTextToken;

            $this->command->info("{$role} token: {$token}");
        }
    }
}
