<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\PreparerModel;
use App\Models\LogModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

            // Requestor cache
            $requestorCacheKey = "requestor_{$requestID}";
            $this->requestEntry = Cache::remember($requestorCacheKey, 3600, function () use ($requestID) {
                return RequestorModel::findOrFail($requestID);
            });

            // Preparer cache
            $preparerCacheKey = "preparer_{$requestID}";
            $this->panEntry = Cache::remember($preparerCacheKey, 3600, function () use ($requestID) {
                return PreparerModel::where('request_id', $requestID)->first();
            });

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

    // DIVISION HEAD

    public function confirmPan(){
        try{
            $this->requestEntry->request_status = 'For HR Approval';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/divisionhead');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function disputeHead(){
        try{
            $this->validate([
                'header' => 'required|string',
                'body' => 'nullable|string'
            ]);

            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin' => 'Dispute Raised by Division Head',
                'header' => 'Subject: ' . $reason,
                'body' => 'Details: ' . $this->body,
                'created_at' => Carbon::now(),
            ]);

            Cache::forget("log_{$this->requestID}");

            $requestEntry = RequestorModel::find($this->requestID);
            $requestEntry->request_status = 'For Resolution';
            $requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Returned to Requestor', 'The request has been returned for correction. Please review the remarks provided.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    // HR PREPARER

    public function submitPan($formData){
        try{
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

                Cache::forget("requestor_{$this->requestID}");
                Cache::forget("preparer_{$this->requestID}");
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
                
                Cache::forget("requestor_{$this->requestID}");
            }


            $this->redirect("/hrpreparer");
            $this->reloadNotif(
                'success',
                'PAN Sent for Confirmation',
                'The prepared PAN form has been sent to the Division Head for confirmation.'
            );
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function resubmitPan($formData){
        try{
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

            Cache::forget("requestor_{$this->requestID}");
            Cache::forget("preparer_{$this->requestID}");

            $this->redirect("/hrpreparer");
            $this->reloadNotif(
                'success',
                'PAN Sent for Confirmation',
                'The prepared PAN form has been sent to the Division Head for confirmation.'
            );            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
        
    }

    // HR APPROVER

    public function approveHr(){
        try{
            $this->requestEntry->request_status = 'For Final Approval';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/hrapprover');
            $this->reloadNotif('success', 'PAN Approved', 'The PAN form has been approved and forwarded to the Final Approver.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrapprover');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function serveHr(){
        try{
            $this->requestEntry->request_status = 'Served';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/hrapprover');
            $this->reloadNotif('success', 'Marked as Served', 'Request successfully marked as Served.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrapprover');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function fileHr(){
        try{
            $this->requestEntry->request_status = 'Filed';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/hrapprover');
            $this->reloadNotif('success', 'Marked as Filed', 'Request successfully marked as Filed.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrapprover');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function rejectHr(){
        try{
            $this->requestEntry->request_status = 'Returned to Requestor';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/hrapprover');
            $this->reloadNotif('success', 'PAN Rejected', 'The PAN form has been rejected and returned to Requestor for revision.');   
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrapprover');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
        
    }

    public function conManila(){
        try{
            $this->requestEntry->confidentiality = 'manila';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");
            
            $this->reloadNotif('success', 'Confidentiality Updated', 'PAN request has been successfully tagged as confidential Manila'); 
            if(Auth::user()->role == 'hrhead'){
                return redirect(request()->header('Referer'));
            } else {
                $this->redirect('/hrpreparer');
            }
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function conTarlac(){
        try{
            $this->requestEntry->confidentiality = 'tarlac';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->reloadNotif('success', 'Confidentiality Updated', 'PAN request has been successfully tagged as confidential Tarlac');   
            if(Auth::user()->role == 'hrhead'){
                $this->redirect('/hrpreparer');
            } else {
                return redirect(request()->header('Referer'));
            }
            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    // FINAL APPROVER

    public function approveFinal(){
        try{
            $this->requestEntry->request_status = 'Approved';
            $this->requestEntry->save();
            $this->panEntry->approved_by = Auth::user()->name;
            $this->panEntry->save();

            Cache::forget("requestor_{$this->requestID}");
            Cache::forget("preparer_{$this->requestID}");

            $this->redirect('/approver');
            $this->reloadNotif('success', 'PAN Approved', 'The PAN has been fully approved and marked as complete.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/approver');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }
    }

    public function rejectFinal(){
        try{
            $this->requestEntry->request_status = 'Returned to Requestor';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->redirect('/approver');
            $this->reloadNotif('success', 'PAN Rejected', 'The PAN form has been rejected and returned to Requestor for revision.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/approver');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    // RENDER

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