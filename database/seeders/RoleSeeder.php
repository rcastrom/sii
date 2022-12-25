<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role=new Role();
        $role->name='admin';
        $role->description='Administrador';
        $role->save();

        $role=new Role();
        $role->name='escolares';
        $role->description='Escolares';
        $role->save();

        $role=new Role();
        $role->name='division';
        $role->description='División';
        $role->save();

        $role=new Role();
        $role->name='personal';
        $role->description='Personal';
        $role->save();

        $role=new Role();
        $role->name='rechumanos';
        $role->description='Recursos Humanos';
        $role->save();


    }
}
