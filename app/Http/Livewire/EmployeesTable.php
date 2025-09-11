<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Employee;

class EmployeesTable extends Component
{
    public function render()
    {   
        $employees = Employee::all();
        return view('livewire.employees-table', compact('employees'));
    }
}
