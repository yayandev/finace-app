<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // User management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Role & Permission management
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',

            // Category management
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',

            // Transaction management
            'view-transactions',
            'create-transactions',
            'edit-transactions',
            'delete-transactions',
            'export-transactions',

            // Report management
            'view-income-report',
            'view-expense-report',
            'view-summary'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles with permissions
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'direktur']);
        $role->givePermissionTo([
            'view-transactions',
            'export-transactions',
            'view-income-report',
            'view-expense-report',
            'view-summary'
        ]);

        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo([
            'view-transactions',
            'create-transactions',
            'export-transactions',
            'view-income-report',
            'view-expense-report',
            'view-summary'
        ]);

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin123@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        User::factory()->create([
            'name' => 'Direktur',
            'email' => 'direktur123@gmail.com',
            'password' => bcrypt('direktur123'),
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user123@gmail.com',
            'password' => bcrypt('user123'),
        ]);

        //assign role admin
        $user = User::find(1);
        $user->assignRole('admin');

        //assign role direktur
        $user = User::find(2);
        $user->assignRole('direktur');

        //assign role user
        $user = User::find(3);
        $user->assignRole('user');
    }
}
