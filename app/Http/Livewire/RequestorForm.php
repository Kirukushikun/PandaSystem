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

    public $mode = 'create';
    public $requestID, $requestEntry, $isDisabled = false;

    // form fields
    public $employee_name, $employee_id, $department, $type_of_action, $justification, $supporting_file;

    public function mount($mode = 'create', $requestID = null, $isDisabled = false)
    {
        $this->mode = $mode;
        $this->requestID = $requestID;
        $this->isDisabled = $isDisabled;

        if ($requestID) {
            $this->requestEntry = RequestorModel::findOrFail($requestID);

            // disable fields if not Draft or Returned
            if (!in_array($this->requestEntry->request_status, ['Draft', 'Returned to Requestor'])) {
                $this->isDisabled = true;
            }

            // auto-fill fields (mass assign)
            $this->fill($this->requestEntry->only([
                'employee_name',
                'employee_id',
                'department',
                'type_of_action',
                'justification'
            ]));
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
            'submitted_at'        => Carbon::now()
        ]);

        $this->dispatch('requestSaved'); // Notify table
        return session()->flash('success', 'Request submitted successfully');
    }

    public function resubmitRequest()
    {   
        $this->validate();

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For Prep';
        $requestEntry->employee_name = $this->employee_name;
        $requestEntry->employee_id = $this->employee_id;
        $requestEntry->department = $this->department;
        $requestEntry->type_of_action = $this->type_of_action;
        $requestEntry->justification = $this->justification;
        $requestEntry->save();

        $this->redirect('/requestor');
        Log::info("Resubmitting {$this->requestID}");
    }

    public function withdrawRequest()
    {   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Withdrew';
        $requestEntry->save();

        $this->redirect('/requestor');
        Log::info("Withdrawing {$this->requestID}");
    }

    public function deleteDraft(){
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->is_deleted_by = [
            "requestor" => true,
            "preparer"  => false,
            "approver"  => false,
        ];
        $requestEntry->save();

        $this->redirect('/requestor');
        Log::info("Deleting Draft {$this->requestID}");
    }
    

    public function render()
    {
        return view('livewire.requestor-form');
    }
}
