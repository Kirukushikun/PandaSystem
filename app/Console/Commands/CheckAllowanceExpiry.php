<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RequestorModel;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use DB;

use App\Mail\AllowanceExpiryMail;
use Illuminate\Support\Facades\Mail;

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

                $expiry = Carbon::parse($prep->doe_to)->startOfDay();
                $now = now()->startOfDay();
                $daysLeft = $now->diffInDays($expiry, false);

                // Add this after calculating $daysLeft
                $notificationWindow = 10; // Days before expiry to start notifying
                $maxExpiredDays = 30; // Stop notifying after 30 days past expiry

                // Only process if within notification window or already expired
                if ($daysLeft > $notificationWindow || $daysLeft < -$maxExpiredDays) {
                    continue; // Skip - too far in the future
                }

                $status = $daysLeft < 0 ? 'expired' : 'pending';

                $currentUrl = url()->current(); 
                $baseUrl = str_contains($currentUrl, 'hrapprover')
                    ? "/hrapprover-view?requestID=" . encrypt($request->id)
                    : "/hrpreparer-view?requestID=" . encrypt($request->id);

                $link = "<a class='text-blue-600 hover:underline font-medium' href='{$baseUrl}'>{$request->request_no}</a>";

                if ($daysLeft < 0) {
                    // Expired
                    $status = 'expired';
                    $daysExpired = abs($daysLeft);
                    $message = "The allowance under {$link} expired <b>{$daysExpired} day" . ($daysExpired > 1 ? 's' : '') . "</b> ago. Please update the employee's record if a new PAN is required.";
                } elseif ($daysLeft == 0) {
                    // Expires today
                    $status = 'pending'; // Use existing status value
                    $message = "The allowance under {$link} will expire <b>today</b>. Please review and take necessary action.";
                } else {
                    // Future expiry
                    $status = 'pending';
                    $message = "The allowance under {$link} will expire in <b>{$daysLeft} day" . ($daysLeft > 1 ? 's' : '') . "</b>. Please review and take necessary action.";
                }


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

                // $receiver = User::where('farm', $request->farm)->first();

                // Send email (example: send to HR or employee)
                Mail::to('i.guno@bfcgroup.org')->send(new AllowanceExpiryMail($message));
            }
        });

        $this->info('Allowance expiry notifications updated.');
    }
}
