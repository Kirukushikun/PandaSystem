<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;

class DivisionheadTable extends Component
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
        // Statuses relevant/visible only to the module
        $statuses = [
            'For Head Approval',
            'For HR Prep',
            'For HR Approval',
            'For Confirmation',
            'For Resolution',
            'Returned to Requestor',
            'Returned to Head',
            'Rejected by Head',
            'Withdrew',
            'Approved'
        ];

        $requests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.requestor') != true")
            ->whereIn('request_status', $statuses)
            ->latest()
            ->paginate(8);

        return view('livewire.divisionhead-table', compact('requests'));
    }
}
