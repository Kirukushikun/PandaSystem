<div class="bg-white shadow rounded-xl p-5 border border-gray-200 flex flex-col gap-4">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h3 class="text-base font-bold text-gray-800">Previous / Legacy Records</h3>
            <p class="text-xs text-gray-500">Manila Confidentiality</p>
        </div>

        @if($this->canUpload())
            <div class="flex items-center gap-2">
                <label class="border border-gray-300 text-sm px-4 py-2 rounded-md hover:bg-gray-100 cursor-pointer max-w-xs truncate">
                    <input type="file" wire:model="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                    <span class="truncate">{{ $file ? $file->getClientOriginalName() : 'Choose File' }}</span>
                </label>
                <button wire:click="saveAttachment" wire:loading.attr="disabled" wire:target="file,saveAttachment"
                    class="bg-gray-700 text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 cursor-pointer disabled:opacity-50 whitespace-nowrap">
                    Upload
                </button>
            </div>
        @endif
    </div>

    @error('file') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

    @if($this->canView())
        <div class="flex flex-col divide-y divide-gray-100">
            @forelse($attachments as $attachment)
                <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">{{ $attachment->file_name }}</h4>
                        <p class="text-xs text-gray-500">Uploaded {{ $attachment->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('employee-attachment.download', $attachment->id) }}" target="_blank"
                            class="border border-gray-300 text-sm px-4 py-2 rounded-md hover:bg-gray-100 cursor-pointer">
                            View / Download
                        </a>
                        @if($this->canUpload())
                            <button wire:click="delete({{ $attachment->id }})"
                                wire:confirm="Remove this attachment? This cannot be undone."
                                class="text-sm px-4 py-2 rounded-md text-red-600 hover:bg-red-50 cursor-pointer">
                                Remove
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-400 py-2">No legacy records uploaded for this employee yet.</div>
            @endforelse
        </div>
    @endif
</div>
