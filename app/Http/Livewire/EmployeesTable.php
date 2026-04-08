<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeesTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $showModal = false;
    public $modalType = null;

    public $employeeId = null;
    public $company_id = '';
    public $employee_name = '';
    public $employee_position = '';
    public $employee_farm = '';
    public $employee_department = '';

    protected $listeners = ['requestSaved' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        $employees = Employee::query()
            ->orderBy('full_name')
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

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalType = 'create';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            $this->noreloadNotif('failed', 'Not Found', 'This employee does not exist.');
            return;
        }

        $this->employeeId = $employee->id;
        $this->company_id = $employee->company_id;
        $this->employee_name = $employee->full_name;
        $this->employee_position = $employee->position;
        $this->employee_farm = $employee->farm;
        $this->employee_department = $employee->department;

        $this->modalType = 'edit';
        $this->showModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->employeeId = $id;
        $this->modalType = 'delete';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalType = null;
    }

    public function createEmployee()
    {
        try {
            Employee::create([
                'company_id' => $this->company_id,
                'full_name' => $this->employee_name,
                'farm' => $this->employee_farm,
                'department' => $this->employee_department,
                'position' => $this->employee_position,
            ]);

            $this->closeModal();
            $this->resetForm();
            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Employee Created', 'New employee has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Create Employee failed: ' . $e->getMessage());
            $this->noreloadNotif('failed', 'Failed', 'Something went wrong while creating employee.');
        }
    }

    public function updateEmployee()
    {
        try {
            $employee = Employee::find($this->employeeId);

            if (!$employee) {
                $this->noreloadNotif('failed', 'Not Found', 'This employee does not exist.');
                return;
            }

            $employee->update([
                'company_id' => $this->company_id,
                'full_name' => $this->employee_name,
                'farm' => $this->employee_farm,
                'department' => $this->employee_department,
                'position' => $this->employee_position,
            ]);

            $this->closeModal();
            $this->resetForm();
            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Employee Updated', 'Employee details updated successfully.');
        } catch (\Exception $e) {
            Log::error('Update Employee failed: ' . $e->getMessage());
            $this->noreloadNotif('failed', 'Failed', 'Something went wrong while updating employee.');
        }
    }

    public function deleteEmployee()
    {
        try {
            $employee = Employee::find($this->employeeId);

            if (!$employee) {
                $this->noreloadNotif('failed', 'Not Found', 'This employee does not exist.');
                return;
            }

            $employee->delete();

            $this->closeModal();
            $this->resetForm();
            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Employee Deleted', 'Employee has been removed successfully.');
        } catch (\Exception $e) {
            Log::error('Delete Employee failed: ' . $e->getMessage());
            $this->noreloadNotif('failed', 'Failed', 'Something went wrong while deleting employee.');
        }
    }

    private function resetForm()
    {
        $this->employeeId = null;
        $this->company_id = '';
        $this->employee_name = '';
        $this->employee_position = '';
        $this->employee_farm = '';
        $this->employee_department = '';
    }

    private function noreloadNotif($type, $header, $message)
    {
        $this->dispatch('notif', type: $type, header: $header, message: $message);
    }
}
