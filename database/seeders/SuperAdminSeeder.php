<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $superAdmin = User::factory()->create(['username' => 'superadmin.gaplabs']);
        } catch(\Exception $e){
            $superAdmin = User::where('username', 'superadmin.gaplabs')->first();
        }


        $superAdmin->assignRole(Role::SUPER_ADMIN->value);
    }
}
