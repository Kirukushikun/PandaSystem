<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\RequestorModel;

class UpdateDepartment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = Employee::all();

        foreach($employees as $employee){
            RequestorModel::where('approver_id', null)
                ->where('request_status', 'Approved')
                ->update([
                    'approver_id' => 64
                ]);
        }
    }
}
