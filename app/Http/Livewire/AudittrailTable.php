<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Audit;
use Livewire\WithPagination;

class AudittrailTable extends Component
{

    use WithPagination;

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {   
        $audits = Audit::latest('created_at')->paginate(20);
        return view('livewire.audittrail-table', compact('audits'));
    }
}
