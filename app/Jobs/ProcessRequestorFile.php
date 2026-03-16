<?php

namespace App\Jobs;

use App\Models\RequestorModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessRequestorFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;
    public $backoff = [10, 30, 60];

    public function __construct(
        public int $requestId,
        public string $tempFilePath,
        public string $originalFileName
    ) {}

    public function handle(): void
    {
        try {
            // Move file from temp location to permanent location
            $permanentPath = 'supporting_files/' . basename($this->tempFilePath);
            
            if (Storage::disk('public')->exists($this->tempFilePath)) {
                Storage::disk('public')->move($this->tempFilePath, $permanentPath);
                
                // Update request with file info
                RequestorModel::where('id', $this->requestId)->update([
                    'supporting_file_url' => $permanentPath,
                    'supporting_file_name' => $this->originalFileName
                ]);

                Log::info('File processed successfully', [
                    'request_id' => $this->requestId,
                    'file_path' => $permanentPath
                ]);
            } else {
                throw new \Exception('Temp file not found: ' . $this->tempFilePath);
            }
        } catch (\Exception $e) {
            Log::error('File processing failed', [
                'request_id' => $this->requestId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('File processing job failed permanently', [
            'request_id' => $this->requestId,
            'error' => $exception->getMessage()
        ]);

        // Mark request as failed
        RequestorModel::where('id', $this->requestId)->update([
            'file_processing_status' => 'failed'
        ]);
    }
}
