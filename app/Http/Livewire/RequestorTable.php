<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;

class RequestorTable extends Component
{   
    protected $listeners = ['requestSaved' => '$refresh'];

    public function render()
    {   
        $myRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.requestor') != true")
            ->latest()
            ->get();

        return view('livewire.requestor-table', compact('myRequests'));
    }
}
