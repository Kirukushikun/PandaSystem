<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\LogModel;

class PreparerLog extends Component
{   
    public $logs;

    public function mount($requestID = null){
        $this->logs = LogModel::where('request_id', $requestID)->get();
    }

    public function render()
    {
        return view('livewire.preparer-log');
    }
}
