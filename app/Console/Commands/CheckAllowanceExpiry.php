<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestorModel; // assuming you have a Pan model
use App\Models\Notification;
use Carbon\Carbon;
use DB;

class CheckAllowanceExpiry extends Command
{
    protected $signature = 'allowance:check-expiry';
    protected $description = 'Check PAN allowances and create/update expiry notifications';

    public function handle()
    {
    // Get approved requests with their preparers
        RequestorModel::select('requestor.*')
        ->whereIn('request_status', ['approved', 'served', 'filed'])
            ->join(DB::raw('(SELECT employee_id, MAX(id) as latest_request_id 
                            FROM requestor 
                            GROUP BY employee_id) as latest'), 
                    'requestor.id', '=', 'latest.latest_request_id')
            ->with(['preparer' => function ($q) {
                $q->where('has_allowances', true);
            }])
->chunk(100, function ($requests) {
    foreach ($requests as $request) {
        $prep = $request->preparer; // hasOne -> single record

        if (! $prep || ! $prep->doe_to) {
            continue; // skip if no preparer or no expiry date
        }

        $expiry = Carbon::parse($prep->doe_to);
        $now = now();
        $daysLeft = ceil(($expiry->timestamp - $now->timestamp) / 86400);

        $status = $daysLeft < 0 ? 'expired' : 'pending';

        $currentUrl = url()->current(); 
        $baseUrl = str_contains($currentUrl, 'hrapprover')
            ? "/hrapprover-view?requestID=" . encrypt($request->id)
            : "/hrpreparer-view?requestID=" . encrypt($request->id);

        $link = "<a class='text-blue-600 hover:underline font-medium' href='{$baseUrl}'>{$request->request_no}</a>";

        $message = $daysLeft < 0
            ? "The allowance under {$link} has expired. Please update the employee’s record if a new PAN is required."
            : ($daysLeft === 0
                ? "The allowance under {$link} will expire today."
                : "Allowance under {$link} will expire in <b>{$daysLeft} day</b>" . ($daysLeft > 1 ? 's' : '') . ". Please review and take necessary action.");

        Notification::updateOrCreate(
            ['pan_id' => $prep->id, 'type' => 'allowance_expiry'],
            [   
                'ref_no' => $request->request_no,
                'message' => $message,
                'days_left' => $daysLeft,
                'status' => $status,
                'is_read' => false,
                'last_notified_at' => now(),
            ]
        );
    }
});

        $this->info('Allowance expiry notifications updated.');
    }
}
