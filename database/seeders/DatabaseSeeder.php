<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::table('users')->insert([
            'id' => 61,
            'role' => 'admin',
            'farm' => 'BFC',
            'name' => 'Iverson Guno',
            'access' => '{"DH_Module": true, "FA_Module": true, "RQ_Module": true, "HRA_Module": true, "HRP_Module": true}'
        ]);

        DB::table('employees')->insert([
            'company_id' => 1234,
            'full_name' => 'Chris P. Bacon',
            'farm' => 'BFC',
            'position' => 'IT Tech Support',
        ]);
    }
}
