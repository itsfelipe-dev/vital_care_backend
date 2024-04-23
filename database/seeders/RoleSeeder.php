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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $user = Role::create(['name' => 'paciente']);
        $admin = Role::create(['name' => 'aux']);

        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo(Permission::all());



    }
}
