<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AccessLog;
use Livewire\WithPagination;

class AccesslogsTable extends Component
{   
    use WithPagination;

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {   
        $logs = AccessLog::paginate(8);
        return view('livewire.accesslogs-table', compact('logs'));
    }
}
