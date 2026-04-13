<?php

namespace App\Console\Commands;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignSuperAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shield:assign-super-admin {--user-id=1 : The ID of the user to assign super_admin role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign super_admin role to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        
        $this->info("Attempting to assign super_admin role to user ID: {$userId}");

        // Find the user
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found!");
            return 1;
        }

        $this->info("Found user: {$user->name} ({$user->email})");

        // Get or create the super_admin role
        $superAdminRoleName = config('filament-shield.super_admin.name', 'super_admin');
        $guardName = Utils::getFilamentAuthGuard();

        $role = Role::firstOrCreate(
            ['name' => $superAdminRoleName, 'guard_name' => $guardName],
            ['name' => $superAdminRoleName, 'guard_name' => $guardName]
        );

        if ($role->wasRecentlyCreated) {
            $this->info("Created new role: {$superAdminRoleName}");
        } else {
            $this->info("Found existing role: {$superAdminRoleName}");
        }

        // Assign the role to the user
        $user->assignRole($role);

        $this->info("Successfully assigned {$superAdminRoleName} role to user {$user->name} (ID: {$user->id})");
        
        // Verify the assignment
        if ($user->hasRole($superAdminRoleName)) {
            $this->info("✅ Role assignment verified!");
        } else {
            $this->error("❌ Role assignment failed!");
            return 1;
        }

        // Show all user roles
        $userRoles = $user->roles->pluck('name')->toArray();
        $this->info("User now has roles: " . implode(', ', $userRoles));

        return 0;
    }
}