<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads; // Import

class RequestorForm extends Component
{      
    use WithFileUploads; // Use it
    public $employee_name, $employee_id, $department, $type_of_action, $justification, $supporting_file;

    protected $rules = [
        'employee_name' => 'required|string',
        'employee_id' => 'required|numeric',
        'department' => 'required|string',
        'type_of_action' => 'required|string',
        'justification' => 'nullable',
        'supporting_file' => 'required|file|max:10240',
    ];
    
    public function submitForm(){
        $this->validate();
        Log::info('Requestor Submitted:', [
            'employee_name' => $this->employee_name,
            'employee_id' => $this->employee_id,
            'department' => $this->department,
            'type_of_action' => $this->type_of_action,
            'justification' => $this->justification,
            'supporting_file' => $this->supporting_file,
        ]);
    }

    public function render()
    {
        return view('livewire.requestor-form');
    }
}
