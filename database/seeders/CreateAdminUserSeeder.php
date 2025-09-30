<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin', 
                'password' => bcrypt('12345678')
            ]
        );

        $role = Role::updateOrCreate(['name' => 'super-admin']);    

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        // Only assign role if not already assigned
        if (!$user->hasRole($role->name)) {
            $user->assignRole([$role->id]);
        }
    }
}