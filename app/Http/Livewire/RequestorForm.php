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
    public $request; //Entry Container
    public $mode, $request_id; // Form State
    public $employee_name, $employee_id, $department, $type_of_action, $justification, $supporting_file; // Form Fields

    public function mount($mode = 'create', $request_id = null)
    {
        $this->mode = $mode;

        if ($request_id) {
            $this->request = RequestorModel::findOrFail($request_id);
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
    
    // Create Mode
    public function submitRequest(){
        $this->validate();

        $requestNo = 'PAN-' . Carbon::now()->year . '-' . rand(100, 999); // e.g., PAN-2025-482

        RequestorModel::create([
            'request_no' => $requestNo, 
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
        $requestNo = 'PAN-' . Carbon::now()->year . '-' . rand(100, 999); // e.g., PAN-2025-482

        RequestorModel::create([
            'request_no' => $requestNo, 
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

    // View Mode
    public function resubmitRequest($targetID){
        Log::info($targetID);
    }

    public function withdrawRequest($targetID){
        Log::info($targetID);
    }
    

    public function render()
    {
        return view('livewire.requestor-form');
    }
}
