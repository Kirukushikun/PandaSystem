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
            'department' => 'IT & Security',
            'position' => 'IT Tech Support',
        ]);


        // DB::table('notifications')->insert([
        //     [
        //         'pan_id' => 1,
        //         'ref_no' => 'PAN-0001',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0001</b> will expire in <b>3 days</b>. Please review and take action.',
        //         'days_left' => 3,
        //         'status' => 'pending',
        //         'is_read' => false,
        //         'last_notified_at' => Carbon::now()->subDays(2),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now()->subDays(2),
        //         'updated_at' => Carbon::now()->subDays(2),
        //     ],
        //     [
        //         'pan_id' => 2,
        //         'ref_no' => 'PAN-0002',
        //         'type' => 'allowance_expiry',
        //         'message' => 'The allowance under <b>PAN-0002</b> expired <b>2 days ago</b>. Please update the employee’s record.',
        //         'days_left' => -2,
        //         'status' => 'expired',
        //         'is_read' => false,
        //         'last_notified_at' => Carbon::now()->subDays(1),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now()->subDays(1),
        //         'updated_at' => Carbon::now()->subDays(1),
        //     ],
        //     [
        //         'pan_id' => 3,
        //         'ref_no' => 'PAN-0003',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0003</b> will expire <b>today</b>. Please review immediately.',
        //         'days_left' => 0,
        //         'status' => 'pending',
        //         'is_read' => true,
        //         'last_notified_at' => Carbon::now(),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'pan_id' => 3,
        //         'ref_no' => 'PAN-0003',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0003</b> will expire <b>today</b>. Please review immediately.',
        //         'days_left' => 0,
        //         'status' => 'pending',
        //         'is_read' => true,
        //         'last_notified_at' => Carbon::now(),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'pan_id' => 3,
        //         'ref_no' => 'PAN-0003',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0003</b> will expire <b>today</b>. Please review immediately.',
        //         'days_left' => 0,
        //         'status' => 'pending',
        //         'is_read' => true,
        //         'last_notified_at' => Carbon::now(),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'pan_id' => 3,
        //         'ref_no' => 'PAN-0003',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0003</b> will expire <b>today</b>. Please review immediately.',
        //         'days_left' => 0,
        //         'status' => 'pending',
        //         'is_read' => true,
        //         'last_notified_at' => Carbon::now(),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'pan_id' => 3,
        //         'ref_no' => 'PAN-0003',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0003</b> will expire <b>today</b>. Please review immediately.',
        //         'days_left' => 0,
        //         'status' => 'pending',
        //         'is_read' => true,
        //         'last_notified_at' => Carbon::now(),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'pan_id' => 3,
        //         'ref_no' => 'PAN-0003',
        //         'type' => 'allowance_expiry',
        //         'message' => 'Allowance under <b>PAN-0003</b> will expire <b>today</b>. Please review immediately.',
        //         'days_left' => 0,
        //         'status' => 'pending',
        //         'is_read' => true,
        //         'last_notified_at' => Carbon::now(),
        //         'resolved_at' => null,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        // ]);
    }
}
