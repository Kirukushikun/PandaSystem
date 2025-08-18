<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\RequestorModel;

class PreparerTable extends Component
{   
    protected $listeners = ['requestSaved' => '$refresh'];

    public function render()
    {
        $panRequests = RequestorModel::whereRaw("JSON_EXTRACT(is_deleted_by, '$.preparer') != true")
            ->whereNot('request_status', 'Draft')
            ->latest()
            ->get();

        return view('livewire.preparer-table', compact('panRequests'));
    }
}
