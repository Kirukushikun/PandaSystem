<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\LogModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ApproverTable extends Component
{   
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $search = '';
    public $filterBy = '';

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public array $selectedRequests = [];

    public $header, $customHeader , $body;

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
    
    public function approveRequests()
    {
        try{
            $count = RequestorModel::whereIn('id', $this->selectedRequests)
                ->update(['request_status' => 'Approved']);
        
            $this->redirect('/approver');
            session()->flash('notif', [
                'type' => 'success',
                'header' => 'Approval Complete',
                'message' => "{$count} request(s) have been approved successfully."
            ]);

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

    public function rejectRequests()
    {   
        try{    
            $count = RequestorModel::whereIn('id', $this->selectedRequests)
                ->update(['request_status' => 'For HR Prep']);

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
            ->latest()
            ->paginate(8);

        return view('livewire.approver-table', compact('approvalRequests'));
    }
    
}
