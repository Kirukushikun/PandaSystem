<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads; // Import
use Carbon\Carbon;

use App\Models\RequestorModel;

class RequestorForm extends Component
{      
    use WithFileUploads; // Use it

    public $mode = 'create', $requestEntry;
    public $request_id;

    // form fields
    public $employee_name, $employee_id, $department, $type_of_action, $justification, $supporting_file;


    public function mount($mode = 'create', $request_id = null){
        $this->mode = $mode;
        $this->request_id = $request_id;

        if ($request_id) {
            $this->requestEntry = RequestorModel::findOrFail($request_id);
            // auto-fill fields
            $this->employee_name  = $this->requestEntry->employee_name;
            $this->employee_id    = $this->requestEntry->employee_id;
            $this->department     = $this->requestEntry->department;
            $this->type_of_action = $this->requestEntry->type_of_action;
            $this->justification  = $this->requestEntry->justification;
        }
    }

    protected $rules = [
        'employee_name' => 'required|string',
        'employee_id' => 'required|numeric',
        'department' => 'required|string',
        'type_of_action' => 'required|string',
        'justification' => 'nullable',
        'supporting_file' => 'required|file|max:10240',
    ];

    private function generateRequestNo()
    {
        return 'PAN-' . Carbon::now()->year . '-' . rand(100, 999);
    }
    
    public function submitRequest(){
        $this->validate();

        RequestorModel::create([
            'request_no'         => $this->generateRequestNo(),
            'request_status'      => 'For Prep',
            'employee_id'         => $this->employee_id,
            'employee_name'       => $this->employee_name,
            'department'          => $this->department,
            'type_of_action'      => $this->type_of_action,
            'justification'       => $this->justification ?? null,
            'supporting_file_url' => $this->supporting_file ?? null,
            'requested_by'        => 'Iverson Craig Guno',
        ]);

        $this->dispatch('requestSaved'); // Notify table
        return session()->flash('success', 'Request submitted successfully');
    }

    public function submitDraft(){

        RequestorModel::create([
            'request_no'         => $this->generateRequestNo(),
            'request_status'      => 'Draft',
            'employee_id'         => $this->employee_id ?? null,
            'employee_name'       => $this->employee_name ?? null,
            'department'          => $this->department ?? null,
            'type_of_action'      => $this->type_of_action ?? null,
            'justification'       => $this->justification ?? null,
            'supporting_file_url' => $this->supporting_file ?? null,
            'requested_by'        => 'Iverson Craig Guno',
        ]);

        $this->dispatch('requestSaved'); // Notify table
        return session()->flash('success', 'Request submitted successfully');
    }


    public function resubmitRequest()
    {   
        $requestEntry = RequestorModel::find($this->request_id);
        $requestEntry->request_status = 'For Prep';
        $requestEntry->save();
        Log::info("Resubmitting {$this->request_id}");
    }

    public function withdrawRequest()
    {
        Log::info("Withdrawing {$this->request_id}");
    }
    

    public function render()
    {
        return view('livewire.requestor-form');
    }
}
