<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Audit;

class AudittrailTable extends Component
{
    public function render()
    {   
        $audits = Audit::get()->sortByDesc('created_at');
        return view('livewire.audittrail-table', compact('audits'));
    }
}
