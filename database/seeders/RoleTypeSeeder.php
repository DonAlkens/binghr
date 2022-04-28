<?php

namespace Database\Seeders;

use App\Models\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        RoleType::create(['name' => 'CEO and Founder', 'permission_id' => 1]);
        RoleType::create(['name' => 'Team Lead', 'permission_id' => 2]);
        RoleType::create(['name' => 'HR', 'permission_id' => 4]);
        RoleType::create(['name' => 'App Designer', 'permission_id' => 3]);
        RoleType::create(['name' => 'Web Developer', 'permission_id' => 3]);

    }
}
