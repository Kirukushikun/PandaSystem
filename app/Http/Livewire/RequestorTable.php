<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;
use Livewire\WithPagination;

class RequestorTable extends Component
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
        $myRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.requestor') != true")
            ->latest()
            ->paginate(8);

        return view('livewire.requestor-table', compact('myRequests'));
    }
}
