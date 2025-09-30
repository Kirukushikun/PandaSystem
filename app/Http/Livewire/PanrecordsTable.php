<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Employee;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PanrecordsTable extends Component
{
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $module;

    public $search = '';

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public function mount($module = null){
        if($module){
            $this->module = $module;
        }
    }

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {   
        $panRecords = Employee::when($this->search, function ($query) {
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
            ->latest('updated_at')
            ->paginate(8);

        return view('livewire.panrecords-table', compact('panRecords'));
    }
}
