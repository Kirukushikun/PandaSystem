<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\LogModel;
use Illuminate\Support\Facades\Cache;

class PreparerLog extends Component
{   
    public $logs;

    public function mount($requestID = null){
        if($requestID){
            // Log cache
            $logCacheKey = "log_{$requestID}";
            $this->logs = Cache::remember($logCacheKey, 3600, function () use ($requestID) {
                return LogModel::where('request_id', $requestID)->latest()->get();
            });
        }

    }

    public function render()
    {
        return view('livewire.preparer-log');
    }
}
