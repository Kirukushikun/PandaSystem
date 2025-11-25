<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Delete only user with ID 61
        $admin = DB::table('users')->where('id', 61)->first();

        if ($admin) {
            DB::table('users')->where('id', 61)->delete();
        }

        DB::table('users')->insert([
            'id' => 61,
            'role' => 'admin',
            'farm' => 'BFC',
            'name' => 'Iverson Guno',
            'access' => '{"DH_Module": true, "FA_Module": true, "RQ_Module": true, "HRA_Module": true, "HRP_Module": true}',
        ]);
    }
}
