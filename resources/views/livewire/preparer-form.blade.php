<section class="content-block relative" id="content-block-1">
    <x-statustag :status-text="$requestEntry->request_status" status-location="Container" />

    <h1 class="text-[22px]">Request Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full">

        <div class="input-fields grid grid-cols md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="input-group">
                <label for="employee_name">Employee Name:</label>
                <input id="employee_name" type="text" value="{{$requestEntry->employee_name}}" readonly>
            </div>
            <div class="input-group">
                <label for="employee_id">Employee ID:</label>
                <input id="employee_id" type="text" value="{{$requestEntry->employee_id}}" readonly>
            </div>
            <div class="input-group">
                <label for="department">Department:</label>
                <input id="department" type="text" value="{{$requestEntry->department}}" readonly>
            </div>
            <div class="input-group">
                <label for="type_of_action">Type of Action:</label>
                <input id="type_of_action" type="text" value="{{$requestEntry->type_of_action}}" readonly>
            </div>
        </div>

        <div class="input-group h-full">
            <label for="justification">Justification:</label>
            <textarea name="justification" id="justification" class="w-full h-50 resize-none" readonly>{{$requestEntry->justification}}</textarea>
        </div>

        <div class="file-group flex flex-col gap-2">
            <label for="supporting_file" class="text-[18px]">Supporting Files:</label>
            <div id="supporting_file" class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm">
                <!-- Button -->
                <button type="button" class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500">
                    View File
                </button>
                <!-- File Name -->
                <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                    sample_document.pdf
                </div>
            </div>
        </div>
        
        <div class="input-group">
            <label>Requested By:</label>
            <p>{{$requestEntry->requested_by}}</p>
        </div>

        <!-- Alpine instance -->
        <div x-data="{ showModal: false, modalMessage: '' }">

            @if($requestEntry->request_status == "For HR Prep")
                <!-- Buttons -->
                <div class="form-buttons bottom-0 right-0 flex gap-3 justify-end md:mb-0 md:absolute">
                    <button type="button" @click="modalMessage = 'Are you sure you want to submit?'; showModal = true" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Return to Requestor</button>
                </div>
            @endif

            <!-- Overlay (instant) -->
            <div x-show="showModal"  class="fixed inset-0 bg-black/50 z-40"></div>

            <!-- Modal box (with transition) -->
            <div
                x-show="showModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="fixed inset-0 flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 z-10 flex flex-col gap-2">
                    <h2 class="text-[22px] text-center font-semibold">Return Request to Requestor</h2>
                    <div class="input-group">
                        <label for="reason">Reason:</label>
                        <select name="reason" id="reason" wire:model="reason">
                            <option value="">Select reason</option>
                            <option value="Missing Supporting File">Missing Supporting File</option>
                            <option value="Incorrect Employee Info">Incorrect Employee Info</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Details:</label>
                        <textarea name="details" id="details" class="w-full h-50 resize-none" placeholder="(Optional)" wire:model="details"></textarea>
                    </div>
                    <div class="flex justify-end mt-2 gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        <button type="button" @click="showModal = false" wire:click="returnRequest" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

