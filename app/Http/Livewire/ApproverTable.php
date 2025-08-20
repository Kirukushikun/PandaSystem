<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;

class ApproverTable extends Component
{   
    protected $listeners = ['requestSaved' => '$refresh'];

    use WithPagination;

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {   
        $approvalRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.approver') != true")
            ->where('request_status', 'For Approval')
            ->orWhere('request_status', 'Approved')
            ->orWhere('request_status', 'Rejected')
            ->latest()
            ->paginate(8);

        return view('livewire.approver-table', compact('approvalRequests'));
    }
}
