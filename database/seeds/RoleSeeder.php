<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin', 'descripcion' => 'rol de admin ves todo', 'estatus' => 1]);
        $role2 = Role::create(['name' => 'Prueba', 'descripcion' => 'rol de prueba no ves nada', 'estatus' => 1]);

        Permission::create(['name' => 'admin.home', 'descripcion' => 'permiso para solo ver el inicio', 'estatus' => 1]);
        Permission::create(['name' => 'admin.permisos.ver', 'descripcion' => 'permiso para ver la pantalla permisos', 'estatus' => 1]);
        Permission::create(['name' => 'admin.permisos.crear', 'descripcion' => 'permiso para crear permisos', 'estatus' => 1]);
        Permission::create(['name' => 'admin.permisos.editar', 'descripcion' => 'permiso para editar los permisos', 'estatus' => 1]);
        Permission::create(['name' => 'admin.permisos.eliminar', 'descripcion' => 'permiso para eliminar permisos', 'estatus' => 1]); 

        $role1->permissions()->attach([1, 2, 3, 4]);

        // No funcionan las funciones nativas nuevas como asyncRoles o asyncPermissions debes usar attach
    }
}
