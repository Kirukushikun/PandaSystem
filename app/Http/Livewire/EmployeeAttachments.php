<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use App\Models\EmployeeAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeAttachments extends Component
{
    use WithFileUploads;

    public Employee $employee;

    public $file;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function updatedFile()
    {
        $this->validateOnly('file', [
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);
    }

    public function canUpload(): bool
    {
        $user = Auth::user();

        return $user->role === 'hrhead' || !empty($user->access['HRA_Module']);
    }

    public function canView(): bool
    {
        $user = Auth::user();

        return $this->canUpload() || !empty($user->access['FA_Module']);
    }

    public function saveAttachment()
    {
        if (!$this->canUpload()) {
            abort(403, 'Unauthorized access to employee attachments.');
        }

        $this->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            $path = $this->file->store('employee_attachments', 'local');

            EmployeeAttachment::create([
                'employee_id' => $this->employee->id,
                'uploaded_by' => Auth::user()->id,
                'file_path' => $path,
                'file_name' => $this->file->getClientOriginalName(),
                'confidentiality' => 'manila',
            ]);

            $this->reset('file');
            $this->dispatch('notif', type: 'success', header: 'Attachment Uploaded', message: 'The record has been added to this employee.');
        } catch (\Exception $e) {
            Log::error('Employee attachment upload failed: ' . $e->getMessage());
            $this->dispatch('notif', type: 'failed', header: 'Upload Failed', message: 'Something went wrong while uploading the attachment.');
        }
    }

    public function delete($attachmentId)
    {
        if (!$this->canUpload()) {
            abort(403, 'Unauthorized access to employee attachments.');
        }

        $attachment = EmployeeAttachment::where('employee_id', $this->employee->id)->findOrFail($attachmentId);

        Storage::disk('local')->delete($attachment->file_path);
        $attachment->delete();

        $this->dispatch('notif', type: 'success', header: 'Attachment Removed', message: 'The record has been removed.');
    }

    public function render()
    {
        $attachments = $this->canView()
            ? EmployeeAttachment::where('employee_id', $this->employee->id)->latest()->get()
            : collect();

        return view('livewire.employee-attachments', compact('attachments'));
    }
}
