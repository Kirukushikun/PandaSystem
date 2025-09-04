<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\PreparerModel;
use App\Models\LogModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PreparerPan extends Component
{   
    public $mode = 'create';
    public $module;
    public $isDisabled, $requestID, $requestEntry, $panEntry, $referenceTableData;
    public $date_hired, $employment_status, $division, $date_of_effectivity, $remarks;
    public 
        $section_from, $section_to,
        $place_from, $place_to,
        $head_from, $head_to,
        $position_from, $position_to,
        $joblevel_from, $joblevel_to,
        $basic_from, $basic_to;

    public $allowances = []; 

    // dispute request fields
    public $header, $customHeader , $body;

    public function mount($module = null, $requestID = null){
        $this->module = $module;
        
        if($requestID){
            // Request existing entry
            $this->requestID = $requestID;
            $this->requestEntry = RequestorModel::findOrFail($requestID); 

            // Pan existing entry
            $this->panEntry = PreparerModel::where('request_id', $requestID)->first();
            if($this->panEntry){
                $this->referenceTableData = $this->panEntry->action_reference_data;    

                $this->date_hired = optional($this->panEntry->date_hired)->format('Y-m-d');
                $this->date_of_effectivity = optional($this->panEntry->date_of_effectivity)->format('Y-m-d');

                // auto-fill fields (mass assign)
                $this->fill($this->panEntry->only([
                    'employment_status',
                    'division',
                    'remarks'
                ]));

                if($this->requestEntry->request_status == "For HR Prep"){
                    $this->mode = 'create';
                }else{
                    $this->mode = 'view';
                }
                
            }

            // disable fields if not Draft or Returned to HR
            if (in_array($this->requestEntry->request_status, ['For HR Prep', 'Returned to HR', 'For Resolution']) && in_array($this->module, ['hr_preparer', 'hr_approver'])) {
                $this->isDisabled = false;
            } else {
                $this->isDisabled = true;
            }

            // determine mode
            if ($this->requestEntry->request_status == 'For Resolution' && $this->module == 'hr_preparer') {
                $this->mode = 'create';
            } 

        }
    }

    protected $rules = [
        'date_hired' => 'required|date',
        'employment_status' => 'required|string',
        'division' => 'required|string',
        'date_of_effectivity' => 'required|date',
        'remarks' => 'nullable|string',
    ];

    // Add this method to receive allowances from Alpine.js
    public function updateAllowances($allowances){
        $this->allowances = $allowances;
    }

    public function submitPan($formData){
        $this->validate();

        $this->requestEntry->request_status = 'For Confirmation';
        $this->requestEntry->save();

        if($this->panEntry){
            // Update existing record
            $this->panEntry->date_hired = $this->date_hired;
            $this->panEntry->employment_status = $this->employment_status;
            $this->panEntry->division = $this->division;
            $this->panEntry->date_of_effectivity = $this->date_of_effectivity;
            $this->panEntry->action_reference_data = $formData;
            $this->panEntry->remarks = $this->remarks;
            $this->panEntry->prepared_by = Auth::user()->name;
            $this->panEntry->save();
        }else{
            PreparerModel::create([
                'request_id' => $this->requestID,
                'date_hired' => $this->date_hired,
                'employment_status' => $this->employment_status,
                'division' => $this->division,
                'date_of_effectivity' => $this->date_of_effectivity,
                'action_reference_data' => $formData,
                'remarks' => $this->remarks,
                'prepared_by' => Auth::user()->name,
            ]);            
        }


        $this->redirect("/hrpreparer");
        $this->reloadNotif(
            'success',
            'PAN Sent for Confirmation',
            'The prepared PAN form has been sent to the Division Head for confirmation.'
        );
    }

    public function resubmitPan($formData){
        $this->validate();

        // Update request status
        $this->requestEntry->request_status = 'For Confirmation';
        $this->requestEntry->save();

        // Fetch existing Preparer entry
        $panEntry = PreparerModel::where('request_id', $this->requestID)->first();

        // Update existing record
        $panEntry->date_hired = $this->date_hired;
        $panEntry->employment_status = $this->employment_status;
        $panEntry->division = $this->division;
        $panEntry->date_of_effectivity = $this->date_of_effectivity;
        $panEntry->action_reference_data = $formData;
        $panEntry->remarks = $this->remarks;
        $panEntry->save();

        $this->redirect("/hrpreparer");
        $this->reloadNotif(
            'success',
            'PAN Sent for Confirmation',
            'The prepared PAN form has been sent to the Division Head for confirmation.'
        );
    }

    public function approveRequest(){
        $this->requestEntry->request_status = 'Approved';
        $this->requestEntry->prepared_by = Auth::user()->name;
        $this->requestEntry->save();

        $this->dispatch('requestSaved');
        Log::info('Approve reqest');
        $this->redirect('/approver');
    }

    public function rejectRequest(){
        $this->requestEntry->request_status = 'Rejected';
        $this->requestEntry->save();

        $this->dispatch('requestSaved');
        Log::info('Reject reqest');
        $this->redirect('/approver');
    }

    public function confirmPan(){
        $this->requestEntry->request_status = 'For HR Approval';
        $this->requestEntry->save();

        $this->redirect('/divisionhead');
    }

    public function disputeHead(){
        $this->validate([
            'header' => 'required|string',
            'body' => 'nullable|string'
        ]);

        $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

        LogModel::create([
            'request_id' => $this->requestID,
            'origin' => 'Dispute Raised by Division Head',
            'header' => 'Subject: ' . $reason,
            'body' => 'Details: ' . $this->body
        ]);

        $requestEntry = RequestorModel::find($this->requestID);
        $requestEntry->request_status = 'For Resolution';
        $requestEntry->save();

        $this->redirect('/divisionhead');
        $this->reloadNotif('success', 'Returned to Requestor', 'The request has been returned for correction. Please review the remarks provided.');
    }

    public function approveHr(){
        $this->requestEntry->request_status = 'For Final Approval';
        $this->requestEntry->save();

        $this->redirect('/hrapprover');
        $this->reloadNotif('success', 'PAN Approved', 'The PAN form has been approved and forwarded to the Final Approver.');
    }

    public function rejectHr(){
        $this->requestEntry->request_status = 'Returned to Requestor';
        $this->requestEntry->save();

        $this->redirect('/hrapprover');
        $this->reloadNotif('success', 'PAN Rejected', 'The PAN form has been rejected and returned to Requestor for revision.');
    }

    public function approveFinal(){
        $this->requestEntry->request_status = 'Approved';
        $this->requestEntry->save();
        $this->panEntry->approved_by = Auth::user()->name;
        $this->panEntry->save();

        $this->redirect('/approver');
        $this->reloadNotif('success', 'PAN Approved', 'The PAN has been fully approved and marked as complete.');
    }

    public function rejectFinal(){
        $this->requestEntry->request_status = 'Returned to Requestor';
        $this->requestEntry->save();

        $this->redirect('/approver');
        $this->reloadNotif('success', 'PAN Rejected', 'The PAN form has been rejected and returned to Requestor for revision.');
    }

    public function render(){
        return view('livewire.preparer-pan');
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