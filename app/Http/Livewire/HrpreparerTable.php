<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;

class HrpreparerTable extends Component
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
            'For HR Prep',
            'For Confirmation',
            'For Resolution',
            'Approved',
            'Rejected'
        ];

        $panRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.preparer') != true")
            ->whereIn('request_status', $statuses)
            ->latest()
            ->paginate(8);

        return view('livewire.hrpreparer-table', compact('panRequests'));
    }
}
