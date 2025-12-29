<?php
// database/seeders/RolesAndPermissionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crie as permissões
        Permission::firstOrCreate(['name' => 'view finance']);
        Permission::firstOrCreate(['name' => 'edit finance']);
        Permission::firstOrCreate(['name' => 'manage users']);

        // 2. Crie os papéis
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleUser = Role::firstOrCreate(['name' => 'user']);

        // 3. Atribua as permissões ao papel 'admin'
        $roleAdmin->givePermissionTo('view finance');
        $roleAdmin->givePermissionTo('edit finance');
        // ATRIBUA A PERMISSÃO 'manage users' ao papel 'admin' AQUI
        $roleAdmin->givePermissionTo('manage users');
    }
}