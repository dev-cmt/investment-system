<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission Groups & Items for Investment System
        $permissionGroups = [
            'Investment Posts' => [
                'view posts',
                'create posts',
                'edit posts',
                'delete posts',
            ],
            'Investments' => [
                'view all investments',
                'view own investments',
                'create investments',
                'edit investments',
                'delete investments',
                'approve investments',
                'reject investments',
            ],
            'Payments' => [
                'view all payments',
                'view own payments',
                'create payments',
                'edit payments',
                'delete payments',
            ],
            'Withdrawals' => [
                'view all withdrawals',
                'view own withdrawals',
                'request withdrawal',
                'manage withdrawals',
            ],
            'Users & Clients' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
            ],
            'Roles & Permissions' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
            'Settings' => [
                'view settings',
                'edit settings',
            ],
        ];

        // Create Permissions
        $allPermissionNames = [];
        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                $allPermissionNames[] = $permissionName;
            }
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $investorRole = Role::firstOrCreate(['name' => 'investor', 'guard_name' => 'web']);

        // Assign All Permissions to Admin
        $adminRole->syncPermissions($allPermissionNames);

        // Assign Investor Specific Permissions
        $investorPermissions = [
            'view own investments',
            'create investments',
            'view own payments',
            'view own withdrawals',
            'request withdrawal',
        ];
        $investorRole->syncPermissions($investorPermissions);

        // Create Default Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('admin12345'),
            ]
        );
        $adminUser->syncRoles([$adminRole]);

        // Create Default Investor User
        $investorUser = User::firstOrCreate(
            ['email' => 'investor@gmail.com'],
            [
                'name' => 'Demo Investor',
                'password' => Hash::make('investor12345'),
            ]
        );
        $investorUser->syncRoles([$investorRole]);
    }
}
