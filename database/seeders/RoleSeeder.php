<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
//use App\Models\Role;
use Spatie\Permission\Models\Role;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        $role=new Role();
        $role->name='admin';
        $role->guard_name='Administrador';
        $role->save();

        $role=new Role();
        $role->name='escolares';
        $role->guard_name='Escolares';
        $role->save();

        $role=new Role();
        $role->name='division';
        $role->guard_name='División';
        $role->save();

        $role=new Role();
        $role->name='personal';
        $role->guard_name='Personal';
        $role->save();

        $role=new Role();
        $role->name='rechumanos';
        $role->guard_name='Recursos Humanos';
        $role->save();

        $role=new Role();
        $role->name='desacad';
        $role->guard_name='Desarrollo Académico';
        $role->save();

        $role=new Role();
        $role->name='academico';
        $role->guard_name='Jefaturas Académicas';
        $role->save();

        $role=new Role();
        $role->name='alumno';
        $role->guard_name='Alumnos';
        $role->save();


    }
}
