<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class HrpreparerTable extends Component
{   
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $search = '';
    public $filterBy = '';
    public $filterFarm = '';

    public $filterStatus = 'all';

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
