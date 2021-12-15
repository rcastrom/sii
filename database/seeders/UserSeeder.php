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
        $role_admin = Role::where('name', 'admin')->first();
        $role_escolares = Role::where('name', 'escolares')->first();

        $user = new User();
        $user->name = 'Ricardo Castro';
        $user->email = 'uno@hotmail.com';
        $user->password =bcrypt('12345');
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Ricardo Castro M';
        $user->email = 'dos@hotmail.com';
        $user->password =bcrypt('12345');
        $user->save();
        $user->roles()->attach($role_escolares);

    }
}
