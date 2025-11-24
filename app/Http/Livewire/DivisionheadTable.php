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
            'Filed',
            'Deleted',
        ];

        $divisionHeadDepartments = [
            // Division Heads
            52  => 'Feedmill',                                    // Lady Arla Lino - 52
            67  => 'General Services',                            // Ancel Roque - 67
            98  => 'Poultry',                                     // Antonio Acibar Jr. - 98
            37  => 'Sales & Marketing',                           // Marie Stephanie Flores - 37
            99  => 'Swine',                                       // Dr. Danhill Lusung - 99
            
            // Shared Services Department Heads
            100 => 'Financial Operations and Compliance',         // Villanueva, Marie Fe - 100
            60  => 'Human Resources',                             // Chrisflor Joy Manalili - 60
            5   => 'IT and Security Services',                    // Montiano, Jeffrey - 5
            63  => 'Purchasing',                                  // Ho, Maria Irene -63

            61  => 'Poultry',                                       // Admin
        ];

        $department = $divisionHeadDepartments[Auth::id()] ?? null;

        $requests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.requestor') != true")
            ->where('is_deleted', false)
            ->where('department', $department)
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
                    'Filed',
                    'Deleted'
                ]);
            })
            ->when($this->filterStatus === 'completed', function ($query) {
                $query->whereIn('request_status', [
                    'Filed',
                    'Deleted'
                ]);
            })
            ->latest('updated_at')
            ->paginate(8);

        return view('livewire.divisionhead-table', compact('requests'));
    }
}
