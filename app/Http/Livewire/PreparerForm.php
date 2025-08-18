<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\RequestorModel;
use App\Models\LogModel;
use Illuminate\Support\Facades\Log;

class PreparerForm extends Component
{   
    public $requestID, $requestEntry;
    public $reason, $details;

    public function mount($requestID = null){
        $this->requestID = $requestID;
        $this->requestEntry = RequestorModel::findOrFail($requestID);
    }

    public function render()
    {
        return view('livewire.preparer-form');
    }

    public function returnRequest(){
        $request = RequestorModel::findOrFail($this->requestID);
        $request->request_status = "Returned to Requestor";
        $request->save();

        LogModel::create([
            'request_id' => $this->requestID,
            'origin' => 'preparer',
            'reason' => $this->reason ?? 'N/A',
            'details' => $this->details ?? 'N/A'
        ]);

        $this->redirect('/preparer');

        Log::info("$this->reason, $this->details");
    }
}
