<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin','user','user_verified'];

        $dbRoles = Role::get();

        foreach ($roles as $role ) {
            if (!$dbRoles->contains('role',$role)) {
                Role::create([
                    'role'=>$role
                ]);
            }
        }
    }
}
