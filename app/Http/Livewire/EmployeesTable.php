<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Employee;
use illuminate\Support\Facades\Log;
use Livewire\WithPagination;

class EmployeesTable extends Component
{   
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public $company_id, $employee_name, $employee_position, $employee_farm, $employee_department;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {   
        $employees = Employee::orderBy('full_name')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('company_id', 'like', '%' . $this->search . '%')
                        ->orWhere('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('farm', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('position', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(20);

        return view('livewire.employees-table', compact('employees'));
    }

    public function createEmployee()
    {
        try {
            Employee::create([
                'company_id' => $this->company_id,
                'full_name'  => $this->employee_name,
                'farm'       => $this->employee_farm,
                'department'       => $this->employee_department,
                'position'   => $this->employee_position,
            ]);

            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Employee Created', 'New employee has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Create Employee failed: ' . $e->getMessage());
            $this->noreloadNotif('failed', 'Failed', 'Something went wrong while creating employee.');
        }
    }


    public function updateEmployee($data)
    {
        $employee = Employee::find($data['id']);

        if (!$employee) {
            $this->noreloadNotif('failed', 'Not Found', 'This employee does not exist.');
            return;
        }

        $employee->update([
            'company_id' => $data['company_id'],
            'full_name'  => $data['name'],
            'farm'       => $data['farm'],
            'department' => $data['department'],
            'position'   => $data['position'],
        ]);

        $this->dispatch('requestSaved');
        $this->reloadNotif('success', 'Employee Updated', 'Employee details updated successfully.');
        $this->redirect('/admin');
    }

    public function deleteEmployee($id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                $this->noreloadNotif('failed', 'Not Found', 'This employee does not exist.');
                return;
            }

            $employee->delete();

            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Employee Deleted', 'Employee has been removed successfully.');
        } catch (\Exception $e) {
            Log::error('Delete Employee failed: ' . $e->getMessage());
            $this->noreloadNotif('failed', 'Failed', 'Something went wrong while deleting employee.');
        }
    }

 // HELPER FUNCTIONS
    private function noreloadNotif($type, $header, $message){
        $this->dispatch('notif', type: $type, header: $header, message: $message);
    }

    private function reloadNotif($type, $header, $message){
        session()->flash('notif', [
            'type' => $type,
            'header' => $header,
            'message' => $message
        ]);
    }
}
