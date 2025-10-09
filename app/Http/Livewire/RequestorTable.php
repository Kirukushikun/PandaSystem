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
        $myRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.requestor') != true")
            ->where('farm', Auth::user()->farm)
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
                    'Filed', 'Withdrew'
                ]);
            })
            ->when($this->filterStatus === 'completed', function ($query) {
                $query->whereIn('request_status', [
                    'Filed', 'Withdrew'
                ]);
            })
            ->latest('updated_at')
            ->paginate(8);

        return view('livewire.requestor-table', compact('myRequests'));
    }
}
