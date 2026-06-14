<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Permiso;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol_escolares=Role::where('name','escolares')->firstOrFail();
        $permisos=Permiso::where('rol','escolares')->get();
        foreach ($permisos as $permiso) {
            $permission=Permission::findOrCreate($permiso->descripcion,'Escolares');
            $rol_escolares->givePermissionTo($permission);
        }
    }
}
