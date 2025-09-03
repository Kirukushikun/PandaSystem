<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\RequestorModel;
use App\Models\LogModel;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class RequestorForm extends Component
{      
    use WithFileUploads;

    public $mode;
    public $module, $requestID, $requestEntry, $isDisabled = false;

    // form fields
    public $employee_name, $employee_id, $department, $type_of_action, $justification;

    public $supporting_file, $reup_supporting_file;

    // return request fields
    public $header, $customHeader , $body;

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
        'supporting_file' => 'nullable|file|mimes:pdf|max:5120'
    ];

    protected $draftRules = [
        'employee_name' => 'nullable|string',
        'employee_id'   => 'nullable|numeric',
        'department'    => 'nullable|string',
        'type_of_action'=> 'nullable|string',
        'justification' => 'nullable',
        'supporting_file' => 'nullable|file|mimes:pdf|max:5120',
    ];

    protected $resubmitRules = [
        'employee_name' => 'required|string',
        'employee_id'   => 'required|numeric',
        'department'    => 'required|string',
        'type_of_action'=> 'required|string',
        'justification' => 'nullable',
        'supporting_file' => 'nullable|file|mimes:pdf|max:5120',
        'reup_supporting_file' => 'nullable|file|mimes:pdf|max:5120'
    ];

    private function generateRequestNo()
    {
        return 'PAN-' . Carbon::now()->year . '-' . rand(100, 999);
    }

    // REQUESTOR

    public function saveDraft(){

        $this->validate($this->draftRules);

        RequestorModel::create([
            'request_no'         => $this->generateRequestNo(),
            'request_status'      => 'Draft',
            'employee_id'         => $this->employee_id ?? null,
            'employee_name'       => $this->employee_name ?? null,
            'department'          => $this->department ?? null,
            'type_of_action'      => $this->type_of_action ?? null,
            'justification'       => $this->justification ?? null,
            'requested_by'        => Auth::user()->name,
        ]);

        $this->dispatch('requestSaved'); // Notify table
        $this->noreloadNotif('success', 'Draft Saved', 'Your request has been saved as a draft.');
    }

    public function deleteDraft(){
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->delete();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Draft Deleted', 'The draft request has been deleted permanently.');
    }

    public function submitDraft(){

        $this->validate();

        if($this->supporting_file){
            // store on the "public" disk in storage/app/public/pdfs
            $path = $this->supporting_file->store('supporting_files', 'public');
            $originalName = $this->supporting_file->getClientOriginalName();            
        }

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For Head Approval';
        $requestEntry->current_handler = 'division head';
        $requestEntry->employee_name = $this->employee_name;
        $requestEntry->employee_id = $this->employee_id;
        $requestEntry->department = $this->department;
        $requestEntry->type_of_action = $this->type_of_action;
        $requestEntry->justification = $this->justification;
        $requestEntry->supporting_file_url = $path ?? null;
        $requestEntry->supporting_file_name = $originalName ?? null;
        $requestEntry->requested_by = Auth::user()->name;
        $requestEntry->submitted_at = Carbon::now();
        $requestEntry->save();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Request Submitted', 'Your request has been submitted for Division Head approval.');
    }

    public function submitRequest(){

        $this->validate();

        if($this->supporting_file){
            // store on the "public" disk in storage/app/public/pdfs
            $path = $this->supporting_file->store('supporting_files', 'public');
            $originalName = $this->supporting_file->getClientOriginalName();            
        }

        RequestorModel::create([
            'request_no'         => $this->generateRequestNo(),
            'request_status'      => 'For Head Approval',
            'current_handler'     => 'division head',
            'employee_id'         => $this->employee_id,
            'employee_name'       => $this->employee_name,
            'department'          => $this->department,
            'type_of_action'      => $this->type_of_action,
            'justification'       => $this->justification ?? null,
            'supporting_file_url' => $path ?? null,
            'supporting_file_name' => $originalName ?? null,
            'requested_by'        => Auth::user()->name,
            'submitted_at'        => Carbon::now()
        ]);

        $this->dispatch('requestSaved'); // Notify table
        $this->noreloadNotif('success', 'Request Submitted', 'Your request has been submitted for Division Head approval.');
        return session()->flash('success', 'Request submitted successfully');
    }

    public function resubmitRequest(){   
        $this->validate($this->resubmitRules);

        // Update common fields
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For Head Approval';
        $requestEntry->employee_name = $this->employee_name;
        $requestEntry->employee_id = $this->employee_id;
        $requestEntry->department = $this->department;
        $requestEntry->type_of_action = $this->type_of_action;
        $requestEntry->justification = $this->justification;

        // Handle re-uploaded supporting file (if any)
        if ($this->reup_supporting_file) {
            // Delete old file if it exists
            if ($this->requestEntry->supporting_file_url) {
                Storage::disk('public')->delete($this->requestEntry->supporting_file_url);
            }

            // Store new file
            $path = $this->reup_supporting_file->store('supporting_files', 'public');
            $originalName = $this->reup_supporting_file->getClientOriginalName();

            // Update file info
            $requestEntry->supporting_file_url = $path;
            $requestEntry->supporting_file_name = $originalName;
        }

        if ($this->supporting_file){
            $path = $this->supporting_file->store('supporting_files', 'public');
            $originalName = $this->supporting_file->getClientOriginalName();  

            $requestEntry->supporting_file_url = $path;
            $requestEntry->supporting_file_name = $originalName;
        }

        // Save everything
        $requestEntry->save();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Request Resubmitted', 'Your request has been successfully resubmitted for processing.');
    }

    public function withdrawRequest(){   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Withdrew';
        $requestEntry->save();

        $this->redirect('/requestor');
        $this->reloadNotif('success', 'Request Withdrawn', 'The request has been withdrawn and will no longer be processed.');
    }

    // DIVISION HEAD

    public function approveRequest(){   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For HR Prep';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Request Approved', 'The request has been approved and forwarded to HR for preparation.');
        Log::info("Withdrawing {$this->requestID}");
    }

    public function rejectRequest(){   
        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Rejected by Head';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Request Rejected', 'The request has been rejected and recorded in the system.');
        Log::info("Withdrawing {$this->requestID}");
    }

    public function returnedHead(){
        $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

        LogModel::create([
            'request_id' => $this->requestID,
            'origin' => 'Returned by Division Head',
            'header' => 'Reason: ' . $reason,
            'body' => 'Details: ' . $this->body
        ]);

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Returned to Requestor';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Request Returned', 'The request has been returned to the requestor for correction.');
    }

    public function returnedHr(){
        $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

        LogModel::create([
            'request_id' => $this->requestID,
            'origin' => 'Returned by HR (Preparer)',
            'header' => 'Reason: ' . $reason,
            'body' => 'Details: ' . $this->body
        ]);

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'Returned to Requestor';
        $requestEntry->save();

        $this->redirect('/hrpreparer');
        $this->reloadNotif('success', 'Request Returned', 'The request has been returned to the requestor for correction.');
    }
    
    //RENDER

    public function render(){
        return view('livewire.requestor-form');
    }

    // HELPER FUNCTIONS

    private function noreloadNotif($type, $header, $message){
        $this->dispatch('notif', type: $type, header: $header, message: $message);
    }

    private function reloadNotif($type, $header, $message){
        session()->flash('notif', [
            'type' => $type,
            'header' => $header,
            'message' => $message
        ]);
    }
}
