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
        $this->statusText = $statusText == 'Returned to Requestor' || $statusText == 'Returned to HR' ? 'Returned' : $statusText;
        $this->statusLocation = $statusLocation;
    }

    public function render()
    {
        return view('components.status-tag');
    }
}
