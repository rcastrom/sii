<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$role_admin = Role::where('name', 'admin')->first();
        $role_escolares = Role::where('name', 'escolares')->first();
        $role_division = Role::where('name', 'division')->first();
        $role_personal = Role::where('name', 'personal')->first();
        $role_humanos = Role::where('name', 'rechumanos')->first();
        $role_desarrollo = Role::where('name','desacad')->first();

        /*$user = new User();
        $user->name = 'Ricardo Castro';
        $user->email = 'rcastro@ite.edu.mx';
        $user->password =bcrypt('Tecnologic0');
        $user->save();
        $user->roles()->attach($role_personal);

        $user = new User();
        $user->name = 'Ricardo Castro M';
        $user->email = 'computo@ite.edu.mx';
        $user->password =bcrypt('Gatha6e9');
        $user->save();
        $user->roles()->attach($role_escolares);

        $user = new User();
        $user->name = 'Ricardo Castro M';
        $user->email = 'computo_d@ite.edu.mx';
        $user->password =bcrypt('Gatha6e9');
        $user->save();
        $user->roles()->attach($role_division);

        $user = new User();
        $user->name = 'Ricardo Castro M';
        $user->email = 'computo_rh@ite.edu.mx';
        $user->password =bcrypt('Gatha6e9');
        $user->save();
        $user->roles()->attach($role_humanos);*/

        $user = new User();
        $user->name = 'Ricardo Castro M';
        $user->email = 'computo_desacad@ite.edu.mx';
        $user->password =bcrypt('Gatha6e9');
        $user->save();
        $user->roles()->attach($role_desarrollo);


    }
}
