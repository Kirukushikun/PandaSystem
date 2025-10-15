<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\PreparerModel;
use App\Models\Employee;
use App\Models\LogModel;
use App\Models\Audit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PreparerPan extends Component
{   
    public $mode = 'create';
    public $module;
    public $isDisabled, $requestID, $requestEntry, $panEntry, $referenceTableData;
    public $date_hired, $employment_status, $division, $date_of_effectivity, $wage_no, $remarks;

    public $date_of_effectivity_from;
    public $date_of_effectivity_to;

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

    // for Request No and Pan Prefill
    public $recentRequestCompleted, $recentPanCompleted, $recentPanCompletedData;

    // for Update Pan
    public $newestRequest, $oldestRequest, $isRecentRequestCompleted, $latestCompletedRequest, $hasNewerOngoing, $canUpdate, $type_of_action;

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

            if($this->requestEntry){    
                $this->division = $this->requestEntry->department;
            }

            if($this->panEntry){
                $this->referenceTableData = $this->panEntry->action_reference_data;    

                $this->date_hired = optional($this->panEntry->date_hired)->format('Y-m-d');
                $this->date_of_effectivity_from = optional($this->panEntry->doe_from)->format('Y-m-d');
                $this->date_of_effectivity_to = optional($this->panEntry->doe_to)->format('Y-m-d');

                // auto-fill fields (mass assign)
                $this->fill($this->panEntry->only([
                    'employment_status',
                    'division',
                    'remarks',
                    'wage_no',
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

            // FIXED: Retrieve previous Request and PAN record base on the employee id
            if($this->requestEntry){    
                $employee_id = $this->requestEntry->employee_id;
                
                // Most recent completed (Approved / Filed / Served)
                $this->recentRequestCompleted = RequestorModel::where('employee_id', $employee_id)
                    ->where('id', '!=', $requestID) // Exclude current request
                    ->whereIn('request_status', ['Approved', 'Served', 'Filed']) // Include completed statuses
                    ->orderBy('created_at', 'desc') // Get the most recent
                    ->first();

                // Always get the most recent COMPLETED request (including the current request)
                $this->latestCompletedRequest = RequestorModel::where('employee_id', $employee_id)
                    ->whereIn('request_status', ['Approved', 'Served', 'Filed'])
                    ->orderBy('created_at', 'desc')
                    ->first();
                                
                // Only try to get PAN record if we found a recent request
                if($this->recentRequestCompleted){
                    $this->recentPanCompleted = PreparerModel::where('request_id', $this->recentRequestCompleted->id)->first();
                    
                    // Only set the data if PAN record exists
                    if($this->recentPanCompleted && $this->requestEntry->confidentiality && !$this->panEntry){ 
                        $this->recentPanCompletedData = $this->recentPanCompleted->action_reference_data;
                        
                        $this->date_hired = optional($this->recentPanCompleted->date_hired)->format('Y-m-d');

                        // auto-fill fields (mass assign)
                        $this->fill($this->recentPanCompleted->only([
                            'employment_status',
                            'division',
                            'remarks',
                            'wage_no',
                        ]));  
                    }
                }

                // Most recent overall (any status)
                $this->newestRequest = RequestorModel::where('employee_id', $employee_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Oldest (if you still need it for your other logic)
                $this->oldestRequest = RequestorModel::where('employee_id', $employee_id)
                    ->orderBy('created_at', 'asc')
                    ->first();

                // Flags
                $this->isRecentRequestCompleted = $this->latestCompletedRequest?->id === $requestID;

                // Is there a newer request that is NOT completed (i.e. ongoing)?
                $this->hasNewerOngoing = $this->newestRequest
                    && $this->newestRequest->id !== $this->latestCompletedRequest?->id
                    && !in_array($this->newestRequest->request_status, ['Approved', 'Filed', 'Served']);

                // Final: allowed to update?
                $this->canUpdate = $this->isRecentRequestCompleted && !$this->hasNewerOngoing;


                // Logging for debugging
                // Log::info("Viewing {$requestID} — recentCompleted: {$this->recentRequestCompleted?->id}, newest: {$this->newestRequest?->id}, oldest: {$this->oldestRequest?->id}");
                // Log::info("RecentRequestCompleted (excluding current): " . ($this->recentRequestCompleted?->id ?? 'none'));
                // Log::info("LatestCompletedRequest (including current): " . ($this->latestCompletedRequest?->id ?? 'none'));
                // Log::info("NewestRequest: " . ($this->newestRequest?->id ?? 'none') . " ({$this->newestRequest?->request_status})");
                // Log::info("isRecentRequestCompleted: " . ($this->isRecentRequestCompleted ? 'true' : 'false'));
                // Log::info("hasNewerOngoing: " . ($this->hasNewerOngoing ? 'true' : 'false'));
                // Log::info("canUpdate: " . ($this->canUpdate ? 'true' : 'false'));
            }

        }
    }

    protected $rules = [
        'date_hired' => 'required|date',
        'employment_status' => 'required|string',
        'division' => 'required|string',
        'remarks' => 'nullable|string',
    ];

    private function generateRequestNo()
    {
        $farmCode = Auth::user()->farm;

        return 'PAN-' . $farmCode . '-' . now()->year . '-' . rand(100, 999);
    }

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
            $this->reloadNotif('success', 'PAN Confirmed', 'The request has been confirmed and will procceed for HR Approval.');               
            
            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Head', 'Confirmed Request');
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

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Head', 'Raised a Dispute');

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Returned to HR', 'The request has been returned for correction. Please review the remarks provided.');            
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

            // Cast to collection for easy searching
            $data = collect($formData);

            // List of tracked allowances
            $targetAllowances = [
                'Developmental Assignments',
                'Interim Allowance',
                'Training Allowance',
            ];

            // Check if any of these exist in the formData
            $hasAllowance = $data->contains(function ($item) use ($targetAllowances) {
                return in_array($item['field'], $targetAllowances);
            });

            $this->validate();
            $this->requestEntry->request_status = 'For Confirmation';
            $this->requestEntry->hr_id = Auth::user()->id;
            $this->requestEntry->save();

            if($this->panEntry){
                // Update existing record
                $this->panEntry->date_hired = $this->date_hired;
                $this->panEntry->employment_status = $this->employment_status;
                $this->panEntry->division = $this->division;
                $this->panEntry->doe_from = $this->date_of_effectivity_from;
                $this->panEntry->doe_to = $this->date_of_effectivity_to;
                $this->panEntry->action_reference_data = $formData;
                $this->panEntry->wage_no = $this->wage_no ?? null;
                $this->panEntry->remarks = $this->remarks;
                $this->panEntry->has_allowances = $hasAllowance;
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
                    'doe_from' => $this->date_of_effectivity_from,
                    'doe_to' => $this->date_of_effectivity_to,
                    'wage_no' => $this->wage_no ?? null,
                    'action_reference_data' => $formData,
                    'remarks' => $this->remarks,
                    'has_allowances' => $hasAllowance,
                    'prepared_by' => Auth::user()->name,
                ]);       
                
                Cache::forget("requestor_{$this->requestID}");
            }

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'Created PAN');

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
            // Cast to collection for easy searching
            $data = collect($formData);

            // List of tracked allowances
            $targetAllowances = [
                'Developmental Assignments',
                'Interim Allowance',
                'Training Allowance',
            ];

            // Check if any of these exist in the formData
            $hasAllowance = $data->contains(function ($item) use ($targetAllowances) {
                return in_array($item['field'], $targetAllowances);
            });

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
            $panEntry->wage_no = $this->wage_no ?? null;
            $panEntry->doe_from = $this->date_of_effectivity_from;
            $panEntry->doe_to = $this->date_of_effectivity_to;
            $panEntry->division = $this->division;
            $panEntry->action_reference_data = $formData;
            $panEntry->has_allowances = $hasAllowance;
            $panEntry->remarks = $this->remarks;
            $panEntry->save();

            Cache::forget("requestor_{$this->requestID}");
            Cache::forget("preparer_{$this->requestID}");

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'Resubmitted PAN');

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

    public function updatePan(){
        try{
            $this->validate([
                'type_of_action' => 'required|string',
            ]);

            $employee = Employee::where('company_id', $this->requestEntry->employee_id)->first();

            $newRequest = RequestorModel::create([
                'request_no' => 'PAN-' . $this->requestEntry->farm . '-' . now()->year . '-' . rand(100, 999),
                'request_status' => 'For HR Prep',
                'employee_id' => $this->requestEntry->employee_id,
                'employee_name' => $this->requestEntry->employee_name,
                'department' => $this->requestEntry->department,
                'farm' => $this->requestEntry->farm,
                'type_of_action' => $this->type_of_action,
                'justification' => $this->requestEntry->justification ?? null,
                'supporting_file_url' => $this->requestEntry->supporting_file_url,
                'supporting_file_name' => $this->requestEntry->supporting_file_name,
                'requested_by' => $this->requestEntry->requested_by,
                'submitted_at' => $this->requestEntry->submitted_at,
            ]);

            // Encrypt the new request ID
            $encryptedId = encrypt($newRequest->id);

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'Updated PAN');

            if (
                ($newRequest->confidentiality === 'manila' && Auth::user()->role === 'hrhead') ||
                ((is_null($newRequest->confidentiality) || $newRequest->confidentiality === 'tarlac') && Auth::user()->role !== 'hrhead')
            ) {
                $this->redirect('/hrpreparer-view?requestID=' . $encryptedId);
            } else {
                $this->redirect('/hrpreparer');
            }

            // Success notification
            $this->reloadNotif(
                'success',
                'PAN Request Created',
                'The PAN update request has been successfully created.'
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

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Head', 'Approved Request');

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

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'Served PAN');

            if($this->module === 'hr_preparer'){
                $this->redirect('/hrpreparer');
            }else{
                $this->redirect('/hrapprover');
            }
           
            $this->reloadNotif('success', 'Marked as Served', 'Request successfully marked as Served.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            if($this->module === 'hr_preparer'){
                $this->redirect('/hrpreparer');
            }else{
                $this->redirect('/hrapprover');
            }
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function fileHr(){
        try{
            $this->requestEntry->request_status = 'Filed';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'Filed PAN');

            if($this->module === 'hr_preparer'){
                $this->redirect('/hrpreparer');
            }else{
                $this->redirect('/hrapprover');
            }

            $this->reloadNotif('success', 'Marked as Filed', 'Request successfully marked as Filed.');            
        }catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            if($this->module === 'hr_preparer'){
                $this->redirect('/hrpreparer');
            }else{
                $this->redirect('/hrapprover');
            }
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function rejectHr(){
        try{
            $this->validate([
                'header' => 'required|string',
                'body' => 'nullable|string'
            ]);

            $this->requestEntry->request_status = 'For HR Prep';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin' => 'Returned by HR Approver',
                'header' => 'Subject: ' . $reason,
                'body' => 'Details: ' . $this->body,
                'created_at' => Carbon::now(),
            ]);

            Cache::forget("log_{$this->requestID}");

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Approver', 'Returned PAN');

            $this->redirect('/hrapprover');
            $this->reloadNotif('success', 'PAN Rejected', 'The PAN form has been rejected and returned to HR Preparer for revision.');   
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

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'PAN Tagged as Manila');
            
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

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'HR Prep', 'PAN Tagged as Tarlac');
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

            if($this->requestEntry->type_of_action == 'Regularization'){
                $this->panEntry->employment_status = 'Regular';
            }

            $this->requestEntry->request_status = 'Approved';
            $this->requestEntry->approver_id = Auth::user()->id;
            $this->requestEntry->save();
            $this->panEntry->approved_by = Auth::user()->name;
            $this->panEntry->save();

            Cache::forget("requestor_{$this->requestID}");
            Cache::forget("preparer_{$this->requestID}");

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'Final Approver', 'Approved PAN');

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
            $this->validate([
                'header' => 'required|string',
                'body' => 'nullable|string'
            ]);

            $this->requestEntry->request_status = 'For HR Prep';
            $this->requestEntry->save();

            Cache::forget("requestor_{$this->requestID}");

            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin' => 'Returned by Final Approver',
                'header' => 'Subject: ' . $reason,
                'body' => 'Details: ' . $this->body,
                'created_at' => Carbon::now(),
            ]);

            Cache::forget("log_{$this->requestID}");

            $this->registerAudit(Auth::user()->id, Auth::user()->name, 'Final Approver', 'Returned PAN');

            $this->redirect('/approver');
            $this->reloadNotif('success', 'PAN Rejected', 'The PAN form has been rejected and returned to HR Preparer for revision.');            
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

    private function registerAudit($userID, $userName, $module, $action){
        Audit::create([
            'user_id' => $userID,
            'name' => $userName,
            'module' => $module,
            'action' => $action
        ]);
    }
}