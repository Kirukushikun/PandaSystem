<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads; // Import
use Carbon\Carbon;

use App\Models\RequestorModel;
use App\Models\LogModel;

class RequestorForm extends Component
{      
    use WithFileUploads; // Use it

    public $mode;
    public $module, $requestID, $requestEntry, $isDisabled = false;

    // form fields
    public $employee_name, $employee_id, $department, $type_of_action, $justification, $supporting_file;

    // return request fields
    public $header, $body;

    public function mount($mode = null, $module = null, $requestID = null, $isDisabled = false)
    {
        $this->mode = $mode;
        $this->module = $module;
        $this->requestID = $requestID;
        $this->isDisabled = $isDisabled;

        if ($requestID) {
            $this->requestEntry = RequestorModel::findOrFail($requestID);

            // disable fields if request status is Draft or Returned and active module is Requestor
            if (in_array($this->requestEntry->request_status, ['Draft', 'Returned to Requestor']) && $this->module == 'requestor') {
                $this->isDisabled = false;
            } else {
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

    // REQUESTOR

    public function saveDraft(){
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
        $this->noreloadNotif('success', 'Draft Saved!', 'Your request has been saved as a draft. You can continue editing anytime.');
    }

    public function submitDraft(){

        $this->validate();

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For Head Approval';
        $requestEntry->current_handler = 'division head';
        $requestEntry->employee_name = $this->employee_name;
        $requestEntry->employee_id = $this->employee_id;
        $requestEntry->department = $this->department;
        $requestEntry->type_of_action = $this->type_of_action;
        $requestEntry->justification = $this->justification;
        $requestEntry->supporting_file_url = $this->supporting_file;
        $requestEntry->submitted_at = Carbon::now();
        $requestEntry->save();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Request Submitted!', 'Your request has been successfully submitted for processing.');
    }

    public function deleteDraft(){
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->delete();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Draft Deleted', 'The draft request has been permanently removed.');
    }

    public function submitRequest(){

        $this->validate();

        RequestorModel::create([
            'request_no'         => $this->generateRequestNo(),
            'request_status'      => 'For Head Approval',
            'current_handler'     => 'division head',
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
        $this->noreloadNotif('success', 'Request Submitted!', 'Your request has been successfully submitted for processing.');
        return session()->flash('success', 'Request submitted successfully');
    }

    public function resubmitRequest()
    {   
        $this->validate();

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For Head Approval';
        $requestEntry->employee_name = $this->employee_name;
        $requestEntry->employee_id = $this->employee_id;
        $requestEntry->department = $this->department;
        $requestEntry->type_of_action = $this->type_of_action;
        $requestEntry->justification = $this->justification;
        $requestEntry->save();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Request Resubmitted!', 'Your request has been successfully resubmitted for processing.');
    }

    public function withdrawRequest()
    {   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Withdrew';
        $requestEntry->save();

        $this->redirect('/requestor');
        Log::info("Withdrawing {$this->requestID}");
    }

    public function submitForPrep(){
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For HR Prep';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        Log::info("Withdrawing {$this->requestID}");
    }

    // DIVISION HEAD

    public function approveRequest()
    {   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For HR Prep';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Request Approved!', 'The request has been approved and now forwarded to HR for preparation.');
        Log::info("Withdrawing {$this->requestID}");
    }

    public function rejectRequest()
    {   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Rejected by Head';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Request Rejected', 'The request has been rejected and recorded in the system.');
        Log::info("Withdrawing {$this->requestID}");
    }

    public function returnedHead(){
        $this->validate([
            'header' => 'required|string',
            'body' => 'nullable|string'
        ]);

        LogModel::create([
            'request_id' => $this->requestID,
            'origin' => 'Returned by Division Head',
            'header' => 'Reason: ' . $this->header,
            'body' => 'Details: ' . $this->body
        ]);

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Returned to Requestor';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Returned to Requestor', 'The request has been returned for correction. Please review the remarks provided.');
    }

    public function returnedHr(){
        $this->validate([
            'header' => 'required|string',
            'body' => 'nullable|string'
        ]);

        LogModel::create([
            'request_id' => $this->requestID,
            'origin' => 'Returned by HR (Preparer)',
            'header' => 'Reason: ' . $this->header,
            'body' => 'Details: ' . $this->body
        ]);

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Returned to Requestor';
        $requestEntry->save();

        $this->redirect('/hrpreparer');
        $this->reloadNotif('success', 'Returned to Requestor', 'The request has been returned for correction. Please review the remarks provided.');
    }
    
    //RENDER

    public function render()
    {
        return view('livewire.requestor-form');
    }

    // HELPER FUNCTIONS

    private function noreloadNotif($type, $header, $message)
    {
        $this->dispatch('notif', type: $type, header: $header, message: $message);
    }

    private function reloadNotif($type, $header, $message)
    {
        session()->flash('notif', [
            'type' => $type,
            'header' => $header,
            'message' => $message
        ]);
    }
}
