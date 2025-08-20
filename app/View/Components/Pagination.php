<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination extends Component
{
    public $paginator;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function render()
    {
        return view('components.pagination');
    }

    public function pageRange()
    {
        $currentPage = $this->paginator->currentPage();
        $lastPage = $this->paginator->lastPage();

        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $start + 4);

        if ($end - $start < 4) {
            $start = max(1, $end - 4);
        }

        return range($start, $end);
    }
}
