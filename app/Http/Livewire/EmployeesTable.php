<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Employee;
use illuminate\Support\Facades\Log;

class EmployeesTable extends Component
{   
    public $company_id, $employee_name, $employee_farm, $employee_position;

    protected $listeners = ['requestSaved' => '$refresh'];

    public function render()
    {   
        $employees = Employee::all();
        return view('livewire.employees-table', compact('employees'));
    }

    public function createEmployee()
    {
        try {
            Employee::create([
                'company_id' => $this->company_id,
                'full_name'  => $this->employee_name,
                'farm'       => $this->employee_farm,
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
                'position'   => $data['position'],
            ]);

            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Employee Updated', 'Employee details updated successfully.');
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
