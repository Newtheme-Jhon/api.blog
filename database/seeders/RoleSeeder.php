<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin          = Role::create(['name' => 'admin']);

        $create_post    = Permission::create(['name' => 'create_post']);
        $edit_post      = Permission::create(['name' => 'edit_post']);
        $delete_post    = Permission::create(['name' => 'delete_post']);

        $admin->syncPermissions([$create_post, $edit_post, $delete_post]);
    }
}
