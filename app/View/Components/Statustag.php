<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Statustag extends Component
{
    public $statusText;
    public $statusLocation;

    public function __construct($statusText, $statusLocation)
    {   
        if (in_array($statusText, ['Returned to Requestor', 'Returned to Head', 'Returned to HR'])) {
            $this->statusText = 'Returned';
        } elseif (in_array($statusText, ['Rejected by Head', 'Rejected by HR', 'Rejected by Approver'])) {
            $this->statusText = 'Rejected';
        } else {
            $this->statusText = $statusText;
        }

        $this->statusLocation = $statusLocation;
    }

    public function render()
    {
        return view('components.status-tag');
    }
}
