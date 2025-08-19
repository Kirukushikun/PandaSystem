<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;

class ApproverTable extends Component
{   
    protected $listeners = ['requestSaved' => '$refresh'];

    public function render()
    {   
        $approvalRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.approver') != true")
            ->where('request_status', 'For Approval')
            ->orWhere('request_status', 'Approved')
            ->orWhere('request_status', 'Rejected')
            ->latest()
            ->get();

        return view('livewire.approver-table', compact('approvalRequests'));
    }
}
