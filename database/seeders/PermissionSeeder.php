<?php

namespace Database\Seeders;

use App\Models\RolePermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        RolePermissions::create(['name' => 'Super Admin']);
        RolePermissions::create(['name' => 'Admin']);
        RolePermissions::create(['name' => 'Employee']);
        RolePermissions::create(['name' => 'HR Admin']);

    }
}
