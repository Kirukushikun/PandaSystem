<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SearchSortFilter extends Component
{   
    public $role;
    public $options;
    public $farmFilter;
    /**
     * Create a new component instance.
     */
    public function __construct($role, $farmFilter = false)
    {
        $this->role = $role;
        $this->farmFilter = $farmFilter;

        $optionsByRole = [
            'requestor' => [
                'Draft', 'For Head Approval', 'For HR Prep', 'For Confirmation',
                'For HR Approval', 'For Resolution', 'For Final Approval',
                'Returned to Requestor', 'Withdrew', 'Approved',
            ],
            'divisionhead' => [
                'For Head Approval', 'For HR Prep', 'For Confirmation', 'For HR Approval',
                'For Resolution', 'For Final Approval', 'Returned to Requestor', 'Withdrew', 'Approved',
            ],
            'hrpreparer' => ['For HR Prep', 'For Confirmation', 'For Resolution'],
            'hrapprover' => ['For HR Approval', 'For Final Approval', 'Approved'],
            'finalapprover' => ['For Final Approval', 'Approved'],
        ];

        $this->options = $optionsByRole[$role] ?? $optionsByRole['requestor'];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.search-sort-filter');
    }
}
