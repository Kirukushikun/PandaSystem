<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\RequestorModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PanrecordsTable extends Component
{
    use WithPagination;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $module, $type_of_action;

    public $search = '';

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public function mount($module = null){
        if($module){
            $this->module = $module;
        }
    }

    public function goToPage($page)
    {
        $this->setPage($page);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatePan($targetUser){
        try{
            $this->validate([
                'type_of_action' => 'required|string',
            ]);

            $employee = Employee::where('company_id', $targetUser)->first();

            $newRequest = RequestorModel::create([
                'request_no' => 'PAN-' . $employee->farm . '-' . now()->year . '-' . rand(100, 999),
                'request_status' => 'For HR Prep',
                'employee_id' => $employee->company_id,
                'employee_name' => $employee->full_name,
                'department' => $employee->department,
                'farm' => $employee->farm,
                'type_of_action' => $this->type_of_action,
                'justification' => null,
                'supporting_file_url' => null,
                'supporting_file_name' => null,
                'submitted_at' => now()
            ]);

            // Success notification
            $this->reloadNotif(
                'success',
                'PAN Update Initiated',
                'The PAN update has been successfully initiated.'
            );

            $this->redirect('/hrpreparer');
        }catch (\Exception $e){
            \Log::error('Processing failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);

            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'We couldn’t proccess your request, please try again.');
        }

    }

    public function render()
    {   
        $panRecords = Employee::when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q  ->where('company_id', 'like', '%' . $this->search . '%')
                        ->orWhere('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('farm', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('position', 'like', '%' . $this->search . '%');
                });
            })
            ->addSelect([
                'has_ongoing' => RequestorModel::selectRaw('COUNT(*)')
                    ->whereColumn('requestor.employee_id', 'employees.company_id') // Changed from employees.id!
                    ->whereNotIn('request_status', ['Approved', 'Filed', 'Served', 'Deleted'])
                    ->where('is_deleted', false)
            ])
            ->latest('updated_at')
            ->paginate(8);


        return view('livewire.panrecords-table', compact('panRecords'));
    }

    private function reloadNotif($type, $header, $message){
        session()->flash('notif', [
            'type' => $type,
            'header' => $header,
            'message' => $message
        ]);
    }
}
