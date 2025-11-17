<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use App\Models\LogModel;
use Livewire\WithPagination;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HrpreparerTable extends Component
{   
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $search = '';
    public $filterBy = '';
    public $filterFarm = '';

    public $filterStatus = 'all';

    public $header, $customHeader, $body;

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

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

    public function updatedFilterFarm()
    {
        $this->resetPage();
    }

    public function updatedSort(){
        $this->resetPage();
    }

    public function deleteEntry($targetEntry){
        try{             
            $this->validate([
                'header' => 'required|string',
                'body' => 'nullable|string'
            ]);

            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $targetEntry,
                'origin' => 'PAN Deleted by HR Preparer',
                'header' => 'Subject: ' . $reason,
                'body' => 'Details: ' . $this->body,
                'created_at' => Carbon::now(),
            ]);

            Cache::forget("log_{$targetEntry}");

            $request = RequestorModel::findOrFail($targetEntry);

            $request->request_status = 'Deleted';
            $request->is_deleted = true;
            $request->save();

            Cache::forget("requestor_{$targetEntry}");

            $this->redirect('/hrpreparer'); 
            session()->flash('notif', [
                'type' => 'success',
                'header' => 'Deletion Success',
                'message' => 'PAN Initiation was successfully deleted'
            ]);

        }catch (\Exception $e) {
            $this->redirect('/hrpreparer'); 
            session()->flash('notif', [
                'type' => 'failed',
                'header' => 'Something went wrong',
                'message' => 'We couldn’t proccess your request, please try again.'
            ]);
        }
    }

    public function render()
    {   
        // Statuses relevant/visible only to the module
        $statuses = [
            'For HR Prep',
            'For Confirmation',
            'For Resolution',
            'For HR Approval',
            'For Final Approval',
            'Approved',
            'Served',
            'Filed'
        ];

        $panRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.preparer') != true")
            ->where('is_deleted', false)
            ->whereIn('request_status', $statuses)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('request_no', 'like', '%' . $this->search . '%')
                        ->orWhere('request_status', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_id', 'like', '%' . $this->search . '%')
                        ->orWhere('employee_name', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('farm', 'like', '%' . $this->search . '%')
                        ->orWhere('type_of_action', 'like', '%' . $this->search . '%')
                        ->orWhere('justification', 'like', '%' . $this->search . '%');
                });
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
            ->when($this->filterFarm, function ($query) {
                $query->where('farm', $this->filterFarm);
            })
            ->when($this->filterBy, function ($query) {
                $query->where('request_status', $this->filterBy);
            })
            ->latest('updated_at')
            ->paginate(8);

        return view('livewire.hrpreparer-table', compact('panRequests'));
    }
}
