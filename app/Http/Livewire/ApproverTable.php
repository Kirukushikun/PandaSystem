<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\PreparerModel;
use App\Models\LogModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ApproverTable extends Component
{   
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $search = '';
    public $filterBy = '';

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public array $selectedRequests = [];
    public $pendingApprovals = [];
    public $target_type;

    public $header, $customHeader , $body;

    public $filterStatus = 'all';

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterBy()
    {
        $this->resetPage();
    }

    public function updatedSort(){
        $this->resetPage();
    }

    public function mount()
    {
        $this->loadPendingApprovals();
    }

    public function loadPendingApprovals()
    {
        // Get all requests that are for final approval
        $this->pendingApprovals = RequestorModel::where('request_status', 'For Final Approval')->get();
    }

    public function approveAll()
    {
        try {
            // Only proceed if there are any pending approvals
            if ($this->pendingApprovals->isEmpty()) {
                session()->flash('notif', [
                    'type' => 'warning',
                    'header' => 'No Pending Requests',
                    'message' => 'There are no requests to approve.',
                ]);
                return;
            }

            $requests = RequestorModel::where('request_status', 'For Final Approval')
                ->where('type_of_action', $this->target_type)
                ->get();

            // Update each and handle related panRequest + cache
            foreach ($requests as $request) {
                $request->update(['request_status' => 'Approved']);

                if ($this->target_type == 'Regularization' && $request->preparer) {
                    $request->preparer->employment_status = 'Regular';
                    $request->preparer->save();
                }

                Cache::forget("requestor_{$request->id}");
                Cache::forget("preparer_{$request->id}");
            }

            // Refresh the collection after approval
            $this->loadPendingApprovals();

            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'success',
                'header' => 'Mass Approval Complete',
                'message' => "{$requests->count()} request(s) have been approved successfully."
            ]);
        } catch (\Exception $e) {
            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'failed',
                'header' => 'Approval Failed',
                'message' => "We couldn’t process your mass approval. Please try again."
            ]);
        }
    }

    public function rejectAll()
    {
        try {
            // Only proceed if there are any pending approvals
            if ($this->pendingApprovals->isEmpty()) {
                session()->flash('notif', [
                    'type' => 'warning',
                    'header' => 'No Pending Requests',
                    'message' => 'There are no requests to reject.',
                ]);
                return;
            }

            $count = RequestorModel::where('request_status', 'For Final Approval')
                ->where('type_of_action', $this->target_type)
                ->update(['request_status' => 'For HR Prep']);


            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;
            
            // Forget cache for all updated requests
            foreach ($this->pendingApprovals as $request) {
                Cache::forget("requestor_{$request->id}");
                Cache::forget("log_{$request->id}");
                LogModel::create([
                    'request_id' => $request->id,
                    'origin' => 'Returned by Final Approver',
                    'header' => 'Subject: ' . $reason,
                    'body' => 'Details: ' . $this->body, 
                    'created_at' => now(),
                ]);
            }

            // Refresh the collection after approval
            $this->loadPendingApprovals();

            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'success',
                'header' => 'Mass Rejection Complete',
                'message' => "{$count} request(s) have been rejected successfully."
            ]);
        } catch (\Exception $e) {
            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'failed',
                'header' => 'Approval Failed',
                'message' => "We couldn’t process your mass approval. Please try again."
            ]);
        }
    }
    
    public function approveRequests()
    {
        try {
            // Get selected requests
            $requests = RequestorModel::whereIn('id', $this->selectedRequests)->get();

            // Track how many were updated
            $count = 0;

            foreach ($requests as $request) {
                // Approve the request
                $request->update(['request_status' => 'Approved']);
                $count++;

                // If the action type is Regularization, update related preparer
                if ($request->type_of_action === 'Regularization' && $request->preparer) {
                    $request->preparer->employment_status = 'Regular';
                    $request->preparer->save();
                }

                // Clear cache
                Cache::forget("requestor_{$request->id}");
                Cache::forget("preparer_{$request->id}");
            }

            // Redirect with success message
            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'success',
                'header' => 'Approval Complete',
                'message' => "{$count} request(s) have been approved successfully."
            ]);

            // Clear selected list
            $this->selectedRequests = [];

        } catch (\Exception $e) {
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'failed',
                'header' => 'Approval Failed',
                'message' => "We couldn’t process your request, please try again."
            ]);
        }
    }

    public function rejectRequests()
    {   
        try{    
            $count = RequestorModel::whereIn('id', $this->selectedRequests)
                ->update(['request_status' => 'For HR Prep']);

            // Forget cache for all updated requestors
            foreach ($this->selectedRequests as $id) {
                Cache::forget("requestor_{$id}");
            }

            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'success',
                'header' => 'Rejection Complete',
                'message' => "{$count} request(s) have been rejected."
            ]);

            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            foreach ($this->selectedRequests as $requestId) {
                LogModel::create([
                    'request_id' => $requestId,
                    'origin' => 'Returned by Final Approver',
                    'header' => 'Subject: ' . $reason,
                    'body' => 'Details: ' . $this->body,
                    'created_at' => now(),
                ]);
            }

            $this->selectedRequests = []; // clear after action
        } catch (\Exception $e) {
            \Log::error('Proccessing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'failed',
                'header' => 'Approval Complete',
                'message' => "We couldn’t proccess your request, please try again."
            ]);
        }
    }
    
    public function render()
    {   
        // Statuses relevant/visible only to the module
        $statuses = [
            'For Final Approval',
            'Approved',
            'Rejected',
            'Served', 
            'Filed'
        ];

        $approvalRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.approver') != true")
            ->whereIn('request_status', $statuses)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('request_no', 'like', '%' . $this->search . '%')
                        ->orWhere('request_status', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_id', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_name', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('type_of_action', 'like', '%' . $this->search . '%')
                        ->orWhere('justification', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterBy, function ($query) {
                $query->where('request_status', $this->filterBy);
            })
            ->when($this->filterStatus === 'in_progress', function ($query) {
                $query->whereNotIn('request_status', [
                    'Filed'
                ]);
            })
            ->when($this->filterStatus === 'completed', function ($query) {
                $query->whereIn('request_status', [
                    'Filed'
                ]);
            })
            ->latest()
            ->paginate(8);

        return view('livewire.approver-table', compact('approvalRequests'));
    }
    
}
