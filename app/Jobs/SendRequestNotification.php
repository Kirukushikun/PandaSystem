<?php

namespace App\Jobs;

use App\Models\RequestorModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    public function __construct(
        public int $requestId,
        public string $notificationType // 'submitted', 'approved', 'rejected', 'returned'
    ) {}

    public function handle(): void
    {
        try {
            $request = RequestorModel::find($this->requestId);
            
            if (!$request) {
                throw new \Exception("Request not found: {$this->requestId}");
            }

            // Send email notification based on type
            // TODO: Implement your email logic here
            
            Log::info('Notification sent', [
                'request_id' => $this->requestId,
                'type' => $this->notificationType
            ]);
        } catch (\Exception $e) {
            Log::error('Notification failed', [
                'request_id' => $this->requestId,
                'type' => $this->notificationType,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
