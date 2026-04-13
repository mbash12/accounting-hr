<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find user with ID 1
        $user = User::find(1);
        
        if (!$user) {
            $this->command->error('User with ID 1 not found!');
            return;
        }

        $this->command->info('Found user: ' . $user->name . ' (' . $user->email . ')');

        // Get or create the super_admin role
        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');
        $guardName = Utils::getFilamentAuthGuard();

        $role = Role::firstOrCreate(
            ['name' => $superAdminRoleName, 'guard_name' => $guardName],
            ['name' => $superAdminRoleName, 'guard_name' => $guardName]
        );

        if ($role->wasRecentlyCreated) {
            $this->command->info("Created new role: {$superAdminRoleName}");
        } else {
            $this->command->info("Found existing role: {$superAdminRoleName}");
        }

        // Assign the role to the user
        $user->assignRole($role);

        $this->command->info("Successfully assigned {$superAdminRoleName} role to user {$user->name} (ID: {$user->id})");
        
        // Verify the assignment
        if ($user->hasRole($superAdminRoleName)) {
            $this->command->info('✅ Role assignment verified!');
        } else {
            $this->command->error('❌ Role assignment failed!');
        }

        // Show all user roles
        $userRoles = $user->roles->pluck('name')->toArray();
        $this->command->info('User now has roles: ' . implode(', ', $userRoles));
    }
}