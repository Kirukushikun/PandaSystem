<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DivisionheadTable extends Component
{   
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public $search = '';
    public $filterBy = '';

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

    public function render()
    {   
        // Statuses relevant/visible only to the module
        $statuses = [
            'For Head Approval',
            'For HR Prep',
            'For Confirmation',
            'For Resolution',
            'For HR Approval',
            'For Final Approval',
            
            'Returned to Requestor',
            'Returned to Head',
            'Rejected by Head',
            'Approved',
            'Served',
            'Filed'
        ];

        $divisionHeadDepartments = [
            67  => 'Engineering',
            // 2  => 'Farm Maintenance', 
            // 3  => 'Feedmill',
            100  => 'Financial Operations and Compliance',
            98  => 'Hatchery',
            60  => 'Human Resources',
            5  => 'IT and Security Services',
            // 8  => 'Logistics',
            // 9  => 'Motorpool',
            99 => 'Production',
            63 => 'Purchasing',
            37 => 'Sales & Marketing',
            // 13 => 'Technical',
        ];

        $department = $divisionHeadDepartments[Auth::id()] ?? null;

        $requests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.requestor') != true")
            ->where('department', $department)
            ->where('farm', Auth::user()->farm)
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
            ->latest('updated_at')
            ->paginate(8);

        return view('livewire.divisionhead-table', compact('requests'));
    }
}
