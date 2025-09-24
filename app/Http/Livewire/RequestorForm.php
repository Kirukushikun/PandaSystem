<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use Carbon\Carbon;


use App\Models\RequestorModel;
use App\Models\LogModel;
use App\Models\Employee;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RequestorForm extends Component
{      
    use WithFileUploads;

    public $mode;
    public $module, $requestID, $requestEntry, $isDisabled = false;

    // form fields
    public $employees;

    public $selected_employee_id; // holds only DB id temporarily

    public $employee_name, $employee_id, $department, $type_of_action, $justification; // Form inputs

    public $supporting_file, $reup_supporting_file; // Form file

    // return request fields
    public $header, $customHeader , $body;

    public function mount($mode = null, $module = null, $requestID = null, $isDisabled = false)
    {
        $this->mode = $mode;
        $this->module = $module;
        $this->requestID = $requestID;
        $this->isDisabled = $isDisabled;

        $this->employees = Employee::where('farm', Auth::user()->farm)->get();

        if ($requestID) {
            // Define cache key
            $cacheKey = "requestor_{$requestID}";

            // Try cache first, fallback to DB
            $this->requestEntry = Cache::remember($cacheKey, 3600, function () use ($requestID) {
                return RequestorModel::findOrFail($requestID);
            });

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

    public function updatedSelectedEmployeeId($value)
    {
        if ($value) {
            $employee = Employee::find($value);

            if ($employee) {
                $this->employee_id = $employee->company_id;
            }
        } else {
            // Reset when no employee is selected
            $this->employee_id = null;
        }
    }

    protected $rules = [
        'employee_name' => 'required|string',
        'employee_id' => 'required|numeric',
        'department' => 'required|string',
        'type_of_action' => 'required|string',
        'justification' => 'nullable',
        'supporting_file' => 'required|file|mimes:pdf|max:5120'
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
        $farmCode = Auth::user()->farm;

        return 'PAN-' . $farmCode . '-' . now()->year . '-' . rand(100, 999);
    }

    protected function isSupervisor()
    {
        $employee = Employee::where('company_id', $this->employee_id)->first();
        return $employee && $employee->position === 'Supervisor';
    }

    // REQUESTOR

    public function saveDraft()
    {
        try {
            $this->validate($this->draftRules);

            RequestorModel::create([
                'request_no'    => $this->generateRequestNo(),
                'request_status'=> 'Draft',
                'employee_id'   => $this->employee_id ?? null,
                'employee_name' => $this->employee_name ?? null,
                'department'    => $this->department ?? null,
                'farm' => Auth::user()->farm,
                'type_of_action'=> $this->type_of_action ?? null,
                'justification' => $this->justification ?? null,
                'requested_by'  => Auth::user()->name,
            ]);

            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Draft Saved', 'Your request has been saved as a draft.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function deleteDraft(){
        try {
            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->delete();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Draft Deleted', 'The draft request has been deleted permanently.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function submitDraft(){
        try {
            $this->validate();

            if($this->supporting_file){
                // store on the "public" disk in storage/app/public/pdfs
                $path = $this->supporting_file->store('supporting_files', 'public');
                $originalName = $this->supporting_file->getClientOriginalName();            
            }

            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'For Head Approval';
            $requestEntry->confidentiality = $this->isSupervisor() ? 'manila' : 'tarlac';
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

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Request Submitted', 'Your request has been submitted for Division Head approval.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function submitRequest(){
        try {
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
                'farm' => Auth::user()->farm,
                'type_of_action'      => $this->type_of_action,
                'justification'       => $this->justification ?? null,
                'supporting_file_url' => $path ?? null,
                'supporting_file_name' => $originalName ?? null,
                'requested_by'        => Auth::user()->name,
                'submitted_at'        => Carbon::now()
            ]);

            $this->dispatch('requestSaved'); // Notify table
            $this->noreloadNotif('success', 'Request Submitted', 'Your request has been submitted for Division Head approval.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);
            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function resubmitRequest(){   
        try {
            $this->validate($this->resubmitRules);

            // Update common fields
            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'For Head Approval';
            $requestEntry->confidentiality =$this->isSupervisor() ? 'manila' : 'tarlac';
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

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Request Resubmitted', 'Your request has been successfully resubmitted for processing.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
        
    }

    public function withdrawRequest(){   
        try {
            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'Withdrew';
            $requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Request Withdrawn', 'The request has been withdrawn and will no longer be processed.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    // DIVISION HEAD

    public function approveRequest(){   
        try {
            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'For HR Prep';
            $requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Request Approved', 'The request has been approved and forwarded to HR for preparation.');
            Log::info("Withdrawing {$this->requestID}");
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function rejectRequest(){   
        try {
            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'Rejected by Head';
            $requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Request Rejected', 'The request has been rejected and recorded in the system.');
            Log::info("Withdrawing {$this->requestID}");
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    // HR PREPARER

    public function returnedHead(){ // Returned by Division Head
        try {
            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin' => 'Returned by Division Head',
                'header' => 'Reason: ' . $reason,
                'body' => 'Details: ' . $this->body,
                'created_at' => Carbon::now(),
            ]);

            Cache::forget("log_{$this->requestID}");

            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'Returned to Requestor';
            $requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Request Returned', 'The request has been returned to the requestor for correction.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function returnedHr(){ // Returned by HR Preparer
        try {
            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin' => 'Returned by HR (Preparer)',
                'header' => 'Reason: ' . $reason,
                'body' => 'Details: ' . $this->body
            ]);

            Cache::forget("log_{$this->requestID}");

            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'Returned to Requestor';
            $requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/hrpreparer');
            $this->reloadNotif('success', 'Request Returned', 'The request has been returned to the requestor for correction.');
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
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
