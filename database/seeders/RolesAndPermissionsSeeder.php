<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'ver dashboard', 'gestionar usuarios', 'gestionar carta',
            'gestionar recetas', 'gestionar almacen', 'gestionar compras',
            'gestionar salon', 'gestionar caja', 'gestionar cocina',
            'gestionar delivery', 'gestionar clientes', 'gestionar facturacion',
            'gestionar reportes', 'gestionar parametros'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $owner = Role::firstOrCreate(['name' => 'Propietario']);
        $owner->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $admin->givePermissionTo(['ver dashboard', 'gestionar usuarios', 'gestionar carta', 'gestionar salon', 'gestionar caja', 'gestionar cocina', 'gestionar reportes']);

        $cajero = Role::firstOrCreate(['name' => 'Cajero']);
        $cajero->givePermissionTo(['ver dashboard', 'gestionar caja', 'gestionar salon', 'gestionar clientes']);

        $mozo = Role::firstOrCreate(['name' => 'Mozo']);
        $mozo->givePermissionTo(['gestionar salon', 'gestionar cocina']);

        $cocinero = Role::firstOrCreate(['name' => 'Cocinero']);
        $cocinero->givePermissionTo(['gestionar cocina']);
    }
}
