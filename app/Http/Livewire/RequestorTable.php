<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RequestorTable extends Component
{   
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $search = '';
    public $filterBy = '';

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

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
        $requestorDepartments = [
            // Feedmill
            70 => 'Feedmill',
            52 => 'Feedmill',

            // General Services
            72 => 'General Services',
            74 => 'General Services',
            75 => 'General Services',
            87 => 'General Services',
            93 => 'General Services',
            95 => 'General Services',

            // Poultry
            81 => 'Poultry',
            73 => 'Poultry',
            83 => 'Poultry',
            84 => 'Poultry',
            86 => 'Poultry',
            88 => 'Poultry',
            89 => 'Poultry',
            90 => 'Poultry',
            91 => 'Poultry',
            92 => 'Poultry',
            56 => 'Poultry',
            26 => 'Poultry',
            97 => 'Poultry',
            98 => 'Poultry',

            // Sales & Marketing
            11 => 'Sales & Marketing',
            35 => 'Sales & Marketing',
            77 => 'Sales & Marketing',
            85 => 'Sales & Marketing',
            6  => 'Sales & Marketing',
            37 => 'Sales & Marketing',

            // Swine
            9  => 'Swine',
            76 => 'Swine',
            79 => 'Swine',
            80 => 'Swine',
            82 => 'Swine',
            96 => 'Swine',
            99 => 'Swine',
            103 => 'Swine',

            // Financial Operations and Compliance
            71 => 'Financial Operations and Compliance',
            78 => 'Financial Operations and Compliance',
            40 => 'Financial Operations and Compliance',
            14 => 'Financial Operations and Compliance',
            39 => 'Financial Operations and Compliance',
            100 => 'Financial Operations and Compliance',

            // Human Resources
            60 => 'Human Resources',
            61 => 'Human Resources',

            // IT and Security Services
            94 => 'IT and Security Services',
            1  => 'IT and Security Services',
            5  => 'IT and Security Services',

            // Purchasing
            24 => 'Purchasing',
            63 => 'Purchasing',
        ];

        $department = $requestorDepartments[Auth::id()] ?? null;

        $myRequests = RequestorModel::where('department', $department)
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
                    'Filed', 'Withdrew', 'Deleted'
                ]);
            })
            ->when($this->filterStatus === 'completed', function ($query) {
                $query->whereIn('request_status', [
                    'Filed', 'Withdrew', 'Deleted'
                ]);
            })
            ->latest('updated_at')
            ->paginate(8);

        return view('livewire.requestor-table', compact('myRequests'));
    }
}
