<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use Carbon\Carbon;

use App\Models\RequestorModel;
use App\Models\LogModel;
use App\Models\Employee;
use App\Models\Audit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RequestorForm extends Component
{      
    use WithFileUploads;

    public $mode;
    public $module, $requestID, $requestEntry, $isDisabled = false;

    // form fields
    public $employees = [];

    public $selected_employee_id;

    public $employee_name, $employee_id, $department, $type_of_action, $justification;

    public $supporting_file, $reup_supporting_file;

    // return request fields
    public $header, $customHeader, $body;

    // ─── Department map ───────────────────────────────────────────────────────

    private static array $requestorDepartments = [
        // Feedmill
        70 => 'Feedmill',
        52 => 'Feedmill',

        // General Services
        72 => 'General Services',
        74 => 'General Services',
        75 => 'General Services',
        87 => 'General Services',
        93 => 'General Services',
        95 => 'General Services',
        67 => 'General Services',
        61 => 'General Services',

        // Poultry
        81 => 'Poultry',
        73 => 'Poultry',
        83 => 'Poultry',
        84 => 'Poultry',
        86 => 'Poultry',
        88 => 'Poultry',
        89 => 'Poultry',
        90 => 'Poultry',
        91 => 'Poultry',
        92 => 'Poultry',
        56 => 'Poultry',
        26 => 'Poultry',
        97 => 'Poultry',
        98 => 'Poultry',

        // Sales & Marketing
        11 => 'Sales & Marketing',
        35 => 'Sales & Marketing',
        77 => 'Sales & Marketing',
        85 => 'Sales & Marketing',
        6  => 'Sales & Marketing',
        37 => 'Sales & Marketing',

        // Swine
        9  => 'Swine',
        76 => 'Swine',
        79 => 'Swine',
        80 => 'Swine',
        82 => 'Swine',
        96 => 'Swine',
        99 => 'Swine',
        103 => 'Swine',

        // Financial Operations and Compliance
        71 => 'Financial Operations and Compliance',
        78 => 'Financial Operations and Compliance',
        40 => 'Financial Operations and Compliance',
        14 => 'Financial Operations and Compliance',
        39 => 'Financial Operations and Compliance',
        100 => 'Financial Operations and Compliance',

        // Human Resources
        60 => 'Human Resources',

        // IT and Security Services
        94 => 'IT and Security Services',
        1  => 'IT and Security Services',
        5  => 'IT and Security Services',

        // Purchasing
        24 => 'Purchasing',
        63 => 'Purchasing',
    ];

    // ─── Mount ────────────────────────────────────────────────────────────────

    public function mount($mode = null, $module = null, $requestID = null, $isDisabled = false)
    {
        $this->mode      = $mode;
        $this->module    = $module;
        $this->requestID = $requestID;
        $this->isDisabled = $isDisabled;

        // Determine department with fallback: map → DB column → null
        $department = self::$requestorDepartments[Auth::id()] ?? null;

        if (!$department) {
            // Fallback: try reading department straight from the employees table
            $authEmployee = Employee::where('company_id', Auth::id())->first()
                         ?? Employee::find(Auth::id());
            $department   = $authEmployee->department ?? null;
        }

        // Load employees for this department; empty collection if none found
        $this->employees = $department
            ? Employee::where('department', $department)->orderBy('full_name')->get()
            : collect();

        if ($requestID) {
            $cacheKey = "requestor_{$requestID}";

            $this->requestEntry = Cache::remember($cacheKey, 3600, function () use ($requestID) {
                return RequestorModel::findOrFail($requestID);
            });

            // Unlock fields only when the request is editable by the requestor
            $this->isDisabled = !(
                in_array($this->requestEntry->request_status, ['Draft', 'Returned to Requestor'])
                && $this->module === 'requestor'
            );

            $this->fill($this->requestEntry->only([
                'employee_id',
                'employee_name',
                'department',
                'type_of_action',
                'justification',
            ]));
        }
    }

    // ─── Employee auto-fill ───────────────────────────────────────────────────

    public function updatedSelectedEmployeeId($value)
    {
        if ($value) {
            $employee = Employee::find($value);

            if ($employee) {
                $this->employee_name = $employee->full_name;
                $this->employee_id   = $employee->company_id;
                $this->department    = $employee->department;
            }
        } else {
            $this->employee_name = null;
            $this->employee_id   = null;
            $this->department    = null;
        }
    }

    // ─── Validation rules ─────────────────────────────────────────────────────

    protected $rules = [
        'employee_name'  => 'required|string',
        'employee_id'    => 'required|numeric',
        'department'     => 'required|string',
        'type_of_action' => 'required|string',
        'justification'  => 'nullable',
        'supporting_file'=> 'required|file|mimes:pdf|max:5120',
    ];

    protected $draftRules = [
        'employee_name'  => 'nullable|string',
        'employee_id'    => 'nullable|numeric',
        'department'     => 'nullable|string',
        'type_of_action' => 'nullable|string',
        'justification'  => 'nullable',
        'supporting_file'=> 'nullable|file|mimes:pdf|max:5120',
    ];

    protected $resubmitRules = [
        'employee_name'       => 'required|string',
        'employee_id'         => 'required|numeric',
        'department'          => 'required|string',
        'type_of_action'      => 'required|string',
        'justification'       => 'nullable',
        'supporting_file'     => 'nullable|file|mimes:pdf|max:5120',
        'reup_supporting_file'=> 'nullable|file|mimes:pdf|max:5120',
    ];

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function generateRequestNo(): string
    {
        $farmCode = Auth::user()->farm;
        return 'PAN-' . $farmCode . '-' . now()->year . '-' . rand(100, 999);
    }

    private function storeFile($file): array
    {
        // Double-check MIME even after Livewire validation (guards slow-connection edge cases)
        $mime = $file->getMimeType();
        if (!in_array($mime, ['application/pdf', 'application/x-pdf'])) {
            throw new \Exception('Only PDF files are accepted. Please re-upload the correct file.');
        }

        $path         = $file->store('supporting_files', 'public');
        $originalName = $file->getClientOriginalName();

        Log::info('File stored', ['path' => $path, 'name' => $originalName, 'user_id' => Auth::id()]);

        return ['path' => $path, 'name' => $originalName];
    }

    private function noreloadNotif(string $type, string $header, string $message): void
    {
        $this->dispatch('notif', type: $type, header: $header, message: $message);
    }

    private function reloadNotif(string $type, string $header, string $message): void
    {
        session()->flash('notif', compact('type', 'header', 'message'));
    }

    private function registerAudit($userID, $userName, $module, $action): void
    {
        Audit::create([
            'user_id' => $userID,
            'name'    => $userName,
            'module'  => $module,
            'action'  => $action,
        ]);
    }

    private function forgetCache(): void
    {
        if ($this->requestID) {
            Cache::forget("requestor_{$this->requestID}");
        }
    }

    // ─── Requestor actions ────────────────────────────────────────────────────

    public function saveDraft()
    {
        try {
            $this->validate($this->draftRules);

            RequestorModel::create([
                'request_no'     => $this->generateRequestNo(),
                'request_status' => 'Draft',
                'employee_id'    => $this->employee_id    ?? null,
                'employee_name'  => $this->employee_name  ?? null,
                'department'     => $this->department     ?? null,
                'farm'           => Auth::user()->farm,
                'type_of_action' => $this->type_of_action ?? null,
                'justification'  => $this->justification  ?? null,
                'requested_by'   => Auth::user()->name,
                'requestor_id'   => Auth::user()->id,
            ]);

            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Draft Saved', 'Your request has been saved as a draft.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // let Livewire surface field errors normally
        } catch (\Exception $e) {
            Log::error('saveDraft failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->noreloadNotif('failed', 'Something went wrong', $e->getMessage() ?: 'Please try again.');
        }
    }

    public function deleteDraft()
    {
        try {
            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->delete();

            $this->forgetCache();

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Draft Deleted', 'The draft has been permanently deleted.');

        } catch (\Exception $e) {
            Log::error('deleteDraft failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'Could not delete the draft. Please try again.');
        }
    }

    public function submitDraft()
    {
        try {
            $this->validate();

            $filePath = $fileName = null;

            if ($this->supporting_file) {
                ['path' => $filePath, 'name' => $fileName] = $this->storeFile($this->supporting_file);
            }

            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->fill([
                'request_status'       => 'For Head Approval',
                'current_handler'      => 'division head',
                'employee_name'        => $this->employee_name,
                'employee_id'          => $this->employee_id,
                'department'           => $this->department,
                'type_of_action'       => $this->type_of_action,
                'justification'        => $this->justification,
                'supporting_file_url'  => $filePath,
                'supporting_file_name' => $fileName,
                'requestor_id'         => Auth::id(),
                'requested_by'         => Auth::user()->name,
                'submitted_at'         => Carbon::now(),
            ])->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'Requestor', 'Submitted Draft');

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Request Submitted', 'Your request has been submitted for Division Head approval.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('submitDraft failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->noreloadNotif('failed', 'Submission Failed', $e->getMessage() ?: 'Please try again.');
        }
    }

    public function submitRequest()
    {
        try {
            $this->validate();

            $filePath = $fileName = null;

            if ($this->supporting_file) {
                ['path' => $filePath, 'name' => $fileName] = $this->storeFile($this->supporting_file);
            }

            RequestorModel::create([
                'request_no'           => $this->generateRequestNo(),
                'request_status'       => 'For Head Approval',
                'current_handler'      => 'division head',
                'employee_id'          => $this->employee_id,
                'employee_name'        => $this->employee_name,
                'department'           => $this->department,
                'farm'                 => Auth::user()->farm,
                'type_of_action'       => $this->type_of_action,
                'justification'        => $this->justification ?? null,
                'supporting_file_url'  => $filePath,
                'supporting_file_name' => $fileName,
                'requestor_id'         => Auth::id(),
                'requested_by'         => Auth::user()->name,
                'submitted_at'         => Carbon::now(),
            ]);

            $this->registerAudit(Auth::id(), Auth::user()->name, 'Requestor', 'Created Request');

            $this->dispatch('requestSaved');
            $this->noreloadNotif('success', 'Request Submitted', 'Your request has been submitted for Division Head approval.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('submitRequest failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace'   => $e->getTraceAsString(),
            ]);
            $this->noreloadNotif('failed', 'Submission Failed', $e->getMessage() ?: 'Please try again.');
        }
    }

    public function resubmitRequest()
    {
        try {
            $this->validate($this->resubmitRules);

            $requestEntry = RequestorModel::findOrFail($this->requestID);

            $requestEntry->fill([
                'request_status' => 'For Head Approval',
                'current_handler'=> 'division head',   // ← was missing, causing routing failures
                'employee_name'  => $this->employee_name,
                'employee_id'    => $this->employee_id,
                'department'     => $this->department,
                'type_of_action' => $this->type_of_action,
                'justification'  => $this->justification,
            ]);

            // Prefer re-upload file; fall back to original supporting_file
            $newFile = $this->reup_supporting_file ?? $this->supporting_file ?? null;

            if ($newFile) {
                // Delete the old file first
                if ($requestEntry->supporting_file_url) {
                    Storage::disk('public')->delete($requestEntry->supporting_file_url);
                }

                ['path' => $path, 'name' => $originalName] = $this->storeFile($newFile);

                $requestEntry->supporting_file_url  = $path;
                $requestEntry->supporting_file_name = $originalName;
            }

            $requestEntry->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'Requestor', 'Resubmitted Request');

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Request Resubmitted', 'Your request has been successfully resubmitted for processing.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('resubmitRequest failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->noreloadNotif('failed', 'Resubmission Failed', $e->getMessage() ?: 'Please try again.');
        }
    }

    public function withdrawRequest()
    {
        try {
            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->request_status = 'Withdrew';
            $requestEntry->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'Requestor', 'Withdrawn Request');

            $this->redirect('/requestor');
            $this->reloadNotif('success', 'Request Withdrawn', 'The request has been withdrawn and will no longer be processed.');

        } catch (\Exception $e) {
            Log::error('withdrawRequest failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->redirect('/requestor');
            $this->reloadNotif('failed', 'Something went wrong', 'Could not withdraw the request. Please try again.');
        }
    }

    // ─── Division Head actions ────────────────────────────────────────────────

    public function approveRequest()
    {
        try {
            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->request_status  = 'For HR Prep';
            $requestEntry->current_handler = 'hr preparer';
            $requestEntry->divisionhead_id = Auth::id();
            $requestEntry->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'Division Head', 'Approved Request');

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Request Approved', 'The request has been approved and forwarded to HR for preparation.');

        } catch (\Exception $e) {
            Log::error('approveRequest failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'Could not approve the request. Please try again.');
        }
    }

    public function rejectRequest()
    {
        try {
            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->request_status = 'Rejected by Head';
            $requestEntry->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'Division Head', 'Rejected Request');

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Request Rejected', 'The request has been rejected and recorded.');

        } catch (\Exception $e) {
            Log::error('rejectRequest failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'Could not reject the request. Please try again.');
        }
    }

    // ─── Return actions ───────────────────────────────────────────────────────

    public function returnedHead()
    {
        try {
            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin'     => 'Returned by Division Head',
                'header'     => 'Reason: ' . $reason,
                'body'       => 'Details: ' . $this->body,
                'created_at' => Carbon::now(),
            ]);

            Cache::forget("log_{$this->requestID}");

            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->request_status  = 'Returned to Requestor';
            $requestEntry->current_handler = 'requestor';
            $requestEntry->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'Division Head', 'Returned Request');

            $this->redirect('/divisionhead');
            $this->reloadNotif('success', 'Request Returned', 'The request has been returned to the requestor for correction.');

        } catch (\Exception $e) {
            Log::error('returnedHead failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->redirect('/divisionhead');
            $this->reloadNotif('failed', 'Something went wrong', 'Could not return the request. Please try again.');
        }
    }

    public function returnedHr()
    {
        try {
            $reason = $this->header === 'Other' ? $this->customHeader : $this->header;

            LogModel::create([
                'request_id' => $this->requestID,
                'origin'     => 'Returned by HR (Preparer)',
                'header'     => 'Reason: ' . $reason,
                'body'       => 'Details: ' . $this->body,
            ]);

            Cache::forget("log_{$this->requestID}");

            $requestEntry = RequestorModel::findOrFail($this->requestID);
            $requestEntry->request_status  = 'Returned to Requestor';
            $requestEntry->current_handler = 'requestor';
            $requestEntry->save();

            $this->forgetCache();
            $this->registerAudit(Auth::id(), Auth::user()->name, 'HR Preparer', 'Returned Request');

            $this->redirect('/hrpreparer');
            $this->reloadNotif('success', 'Request Returned', 'The request has been returned to the requestor for correction.');

        } catch (\Exception $e) {
            Log::error('returnedHr failed: ' . $e->getMessage(), ['user_id' => Auth::id()]);
            $this->redirect('/hrpreparer');
            $this->reloadNotif('failed', 'Something went wrong', 'Could not return the request. Please try again.');
        }
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.requestor-form');
    }
}