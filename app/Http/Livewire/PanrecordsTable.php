<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use App\Models\Employee;
use App\Models\RequestorModel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PanrecordsTable extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $listeners = ['requestSaved' => '$refresh'];

    public $module, $type_of_action;

    public $search = '';

    protected $paginationTheme = 'tailwind'; // or 'bootstrap' or omit

    public $supporting_file;

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

            // Initialize local variables for file storage
            $filePath = null;
            $fileName = null;

            // Handle file upload if present
            if($this->supporting_file){
                try {
                    // Store file in storage/app/public/supporting_files
                    $filePath = $this->supporting_file->store('supporting_files', 'public');
                    $fileName = $this->supporting_file->getClientOriginalName();
                    
                    // Log successful upload for debugging
                    \Log::info('File uploaded successfully', [
                        'path' => $filePath,
                        'original_name' => $fileName,
                        'user_id' => Auth::id()
                    ]);
                } catch (\Exception $e) {
                    \Log::error('File upload failed: ' . $e->getMessage(), [
                        'user_id' => Auth::id(),
                    ]);
                    throw new \Exception('File upload failed. Please try again.');
                }
            }

            $employee = Employee::findOrFail($targetUser);

            $newRequest = RequestorModel::createWithGeneratedRequestNo([
                'request_status' => 'For HR Prep',
                'employee_id' => $employee->company_id,
                'employee_name' => $employee->full_name,
                'department' => $employee->department,
                'farm' => $employee->farm,
                'type_of_action' => $this->type_of_action,
                'justification' => null,
                'supporting_file_url' => $filePath,
                'supporting_file_name' => $fileName,
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
        $activeStatuses = [
            'For Head Approval',
            'For HR Prep',
            'For Confirmation',
            'For HR Approval',
            'For Resolution',
            'For Final Approval',
            'Returned to HR',
        ];

        $panRecords = Employee::when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('company_id', 'like', '%' . $this->search . '%')
                        ->orWhere('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('farm', 'like', '%' . $this->search . '%')
                        ->orWhere('department', 'like', '%' . $this->search . '%')
                        ->orWhere('position', 'like', '%' . $this->search . '%');
                });
            })
            ->addSelect([
                'has_ongoing' => RequestorModel::selectRaw('COUNT(*)')
                    ->whereColumn('requestor.employee_id', 'employees.company_id')
                    ->whereColumn('requestor.employee_name', 'employees.full_name')
                    ->whereIn('request_status', $activeStatuses)
                    ->where('is_deleted', false),

                'ongoing_pan_status' => RequestorModel::select('request_status')
                    ->whereColumn('requestor.employee_id', 'employees.company_id')
                    ->whereColumn('requestor.employee_name', 'employees.full_name')
                    ->whereIn('request_status', $activeStatuses)
                    ->where('is_deleted', false)
                    ->latest('updated_at')
                    ->limit(1),

                'ongoing_pan_request_no' => RequestorModel::select('request_no')
                    ->whereColumn('requestor.employee_id', 'employees.company_id')
                    ->whereColumn('requestor.employee_name', 'employees.full_name')
                    ->whereIn('request_status', $activeStatuses)
                    ->where('is_deleted', false)
                    ->latest('updated_at')
                    ->limit(1),
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
