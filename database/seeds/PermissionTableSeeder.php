<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'attendance-list',
            'attendance-create',
            'attendance-edit',
            'attendance-delete'
        ];
    
    
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
