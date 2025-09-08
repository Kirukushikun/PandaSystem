

<form class="h-full {{$mode == 'create' ? 'pb-10' : ''}}" wire:submit.prevent="submitForm" enctype="multipart/form-data">
    <!-- -- Status only in view mode -- -->
    @if($mode === 'view')
        <x-statustag :status-text="$requestEntry->request_status" status-location="Container"/>
    @endif

    <h1 class="text-[22px] pb-4">Request Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full" 
        x-data="{
            showModal: false,
            showAction: false,

            modalTarget: '',
            modalConfig: {
                submit: {
                    header: 'Submit Request',
                    message: 'Are you sure you want to submit this request for processing?',
                    action: 'submitRequest',
                    needsInput: false
                },
                resubmit: {
                    header: 'Resubmit Request',
                    message: 'Are you sure you want to resubmit this request after making the necessary corrections?',
                    action: 'resubmitRequest',
                    needsInput: false
                },
                

                savedraft: {
                    header: 'Save Draft',
                    message: 'Do you want to save this request as a draft for now?',
                    action: 'saveDraft',
                    needsInput: false
                },
                submitdraft: {
                    header: 'Submit Draft',
                    message: 'Are you sure you want to submit this draft request for processing?',
                    action: 'submitDraft',
                    needsInput: false
                },
                deletedraft: {
                    header: 'Delete Draft',
                    message: 'Are you sure you want to delete this draft? This action cannot be undone.',
                    action: 'deleteDraft',
                    needsInput: false
                },
                withdraw: {
                    header: 'Withdraw Request',
                    message: 'Are you sure you want to withdraw this request? Once withdrawn, it cannot be restored.',
                    action: 'withdrawRequest',
                    needsInput: false
                },

                approverequest: {
                    header: 'Approve Request',
                    message: 'Do you want to approve this request and forward it to HR for preparation?',
                    action: 'approveRequest',
                    needsInput: false
                },
                rejectrequest: {
                    header: 'Reject Request',
                    message: 'Are you sure you want to reject this request? This action will be recorded.',
                    action: 'rejectRequest',
                    needsInput: false
                },

                returnedhead: {
                    header: 'Return Request to Requestor',
                    action: 'returnedHead',
                    needsInput: true
                },
                returnedhr: {
                    header: 'Return Request to Head',
                    action: 'returnedHr',
                    needsInput: true
                },
            },

            // helper to collect all fields
            get fields(){
                return [
                    this.$refs.employee_name,
                    this.$refs.employee_id,
                    this.$refs.department,
                    this.$refs.type_of_action,
                    this.$refs.justification,
                ];
            },

            // Initialize their event listeners to a function
            init() {
                this.fields.forEach(field => {
                    field.addEventListener('input', () => this.checkFields());
                    field.addEventListener('change', () => this.checkFields()); // for file input
                });
            },

            checkFields() {
                this.showAction = this.fields.some(f => f.value?.trim() !== '');
            },

            validateBeforeModal(action) {

                let hasEmpty = false;

                this.fields.forEach(field => {
                    if (field.name === 'justification') return;

                    if (field.value.trim() === '') {
                        field.classList.add('!border-red-500'); // highlight
                        hasEmpty = true;
                    } else {
                        field.classList.remove('!border-red-500'); // remove highlight if fixed
                    }
                });

                if (hasEmpty) return; // stop if any empty

                this.modalTarget = action;
                this.showModal = true;
            },
            
            resetForm(){
                this.fields.forEach(field => {
                    field.value = '';
                    field.classList.remove('!border-red-500');
                    this.checkFields();
                });
            }
            
        }"
    > 
        <div class="input-fields grid grid-cols md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="input-group">
                <label for="employee_name">Employee Name:</label>
                <input type="text" name="employee_name" id="employee_name" x-ref="employee_name" wire:model="employee_name" {{$isDisabled ? 'Readonly' : ''}}>
            </div>
            <div class="input-group">
                <label for="employee_id">Employee ID:</label>
                <input type="text" name="employee_id" id="employee_id" x-ref="employee_id" wire:model="employee_id" {{$isDisabled ? 'Readonly' : ''}}>
            </div>
            <div class="input-group">
                <label for="department">Department:</label>
                <select name="department" id="department" x-ref="department" x-ref="department" wire:model="department" {{$isDisabled ? 'Disabled' : ''}}>
                    <option value="">Select department</option>
                    <option value="FEEDMILL">FEEDMILL</option>
                    <option value="FOC">FOC</option>
                    <option value="GENERAL SERVICES">GENERAL SERVICES</option>
                    <option value="HR">HR</option>
                    <option value="IT & SECURITY">IT & SECURITY</option>
                    <option value="POULTRY">POULTRY</option>
                    <option value="PURCHASING">PURCHASING</option>
                    <option value="SALES & MARKETING">SALES & MARKETING</option>
                    <option value="SWINE">SWINE</option>
                </select>
            </div>
            <div class="input-group">
                <label for="type_of_action">Type of Action:</label>
                <select name="type_of_action" id="type_of_action" x-ref="type_of_action" wire:model="type_of_action" {{$isDisabled ? 'Disabled' : ''}}>
                    <option value="">Select type</option>
                    <option value="Regularization">Regularization</option>
                    <option value="Salary Alignment">Salary Alignment</option>
                    <option value="Wage Order">Wage Order</option>
                    <option value="Lateral Transfer">Lateral Transfer</option>
                    <option value="Developmental Assignment">Developmental Assignment</option>
                    <option value="Interim Allowance">Interim Allowance</option>
                    <option value="Promotion">Promotion</option>
                    <option value="Training Status">Training Status</option>
                    <option value="Confirmation of Appointment">Confirmation of Appointment</option>
                    <option value="Discontinuance of Interim Allowance">Discontinuance of Allowance</option>
                    <option value="Confirmation of Development Assignment">Confirmation of Dev. Assignment</option>
                </select>
            </div>
        </div>

        <div class="input-group h-full">
            <label for="justification">Justification:</label>
            <textarea class="resize-none {{$mode == 'view' ? 'h-[200px]' : 'h-full'}}" name="justification" id="justification" class="w-full h-full resize-none" x-ref="justification" wire:model="justification" {{$isDisabled ? 'Readonly' : ''}}></textarea>
        </div>

        @if($mode == 'create')
            <!-- Create -->
            <div class="file-group flex flex-col gap-2">
                <label for="supporting_file" class="text-[18px] relative">Supporting File: 
                    @error('supporting_file')
                        <span class="absolute bg-white text-red-600 right-[10px] bottom-[-20px] text-xs p-1">
                            {{ $message }}
                        </span>
                    @enderror
                </label>
                <input name="supporting_file" id="supporting_file" class="block w-full text-sm text-gray-500 border border-1 {{ $errors->has('supporting_file') ? 'border-red-600' : 'border-gray-600' }} rounded-md cursor-pointer bg-gray-50 focus:outline-none" type="file" accept="application/pdf" x-ref="supporting_file" wire:model="supporting_file">
            </div>
        @elseif($mode == 'view')
            @if($module == 'requestor')
                @if($requestEntry->request_status == 'Returned to Requestor')
                    @if($requestEntry->supporting_file_url)
                        <div class="grid grid-cols-2 gap-5">
                            <div class="existing-file flex flex-col gap-3">
                                <label for="supporting_file" class="text-[18px] relative">Existing File: <span class="text-gray-400">Currently Attached File</span></label>
                                <div class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm mb-2">
                                    <a href="{{ Storage::url($requestEntry->supporting_file_url) }}" target="_blank" class="bg-gray-600 text-white px-4 py-2.5 hover:bg-gray-500">
                                        View File
                                    </a>
                                    <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                                        {{ $requestEntry->supporting_file_name }}
                                    </div>
                                </div>                                
                            </div>

                            <div class="reupload-file flex flex-col gap-3">
                                <!-- Re-upload Input -->
                                <label for="reup_supporting_file" class="text-[18px] relative">Re-upload: <span class="text-gray-400">Upload New File (optional)</span>
                                    @error('reup_supporting_file')
                                        <span class="absolute bg-white text-red-600 right-[10px] bottom-[-20px] text-xs p-1">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </label>
                                <input name="reup_supporting_file" id="reup_supporting_file" type="file"
                                    accept="application/pdf"
                                    class="block w-full text-sm text-gray-500 border border-1 
                                    {{ $errors->has('reup_supporting_file') ? 'border-red-600' : 'border-gray-600' }} 
                                    rounded-md cursor-pointer bg-gray-50 focus:outline-none"
                                    wire:model="reup_supporting_file">

                                <small class="text-gray-500">Leave empty if you don't want to change the file.</small>                                
                            </div>
                        </div>
                    @else
                         <div class="file-group flex flex-col gap-2">
                            <label for="supporting_file" class="text-[18px] relative">Supporting File: 
                                @error('supporting_file')
                                    <span class="absolute bg-white text-red-600 right-[10px] bottom-[-20px] text-xs p-1">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </label>
                            <input name="supporting_file" id="supporting_file" class="block w-full text-sm text-gray-500 border border-1 {{ $errors->has('supporting_file') ? 'border-red-600' : 'border-gray-600' }} rounded-md cursor-pointer bg-gray-50 focus:outline-none" type="file" accept="application/pdf" x-ref="supporting_file" wire:model="supporting_file">
                        </div>
                    @endif
                @elseif($requestEntry->request_status == 'Draft')
                    <div class="file-group flex flex-col gap-2">
                        <label for="supporting_file" class="text-[18px] relative">Supporting File: 
                            @error('supporting_file')
                                <span class="absolute bg-white text-red-600 right-[10px] bottom-[-20px] text-xs p-1">
                                    {{ $message }}
                                </span>
                            @enderror
                        </label>
                        <input name="supporting_file" id="supporting_file" class="block w-full text-sm text-gray-500 border border-1 {{ $errors->has('supporting_file') ? 'border-red-600' : 'border-gray-600' }} rounded-md cursor-pointer bg-gray-50 focus:outline-none" type="file" accept="application/pdf" x-ref="supporting_file" wire:model="supporting_file">
                    </div>
                @else
                    <div class="file-group flex flex-col gap-2">
                        <label for="" class="text-[18px]">Supporting Files:</label>
                        <div class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm">
                            @if($requestEntry->supporting_file_url)
                                <a href="{{ Storage::url($requestEntry->supporting_file_url) }}" target="_blank" type="button" class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500" x-ref="supporting_file">
                                    View File
                                </a>
                                <!-- File Name -->
                                <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                                    {{$requestEntry->supporting_file_name}}
                                </div>                        
                            @else
                                <div target="_blank" type="button" class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500" x-ref="supporting_file" disabled>
                                    View File
                                </div>
                                <!-- File Name -->
                                <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                                    No file attached
                                </div> 
                            @endif
                            <!-- Button -->
                        </div>
                    </div>
                @endif
            @else
                <div class="file-group flex flex-col gap-2">
                    <label for="" class="text-[18px]">Supporting Files:</label>
                    <div class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm">
                        @if($requestEntry->supporting_file_url)
                            <a href="{{ Storage::url($requestEntry->supporting_file_url) }}" target="_blank" type="button" class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500" x-ref="supporting_file">
                                View File
                            </a>
                            <!-- File Name -->
                            <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                                {{$requestEntry->supporting_file_name}}
                            </div>                        
                        @else
                            <div target="_blank" type="button" class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500" x-ref="supporting_file" disabled>
                                View File
                            </div>
                            <!-- File Name -->
                            <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                                No file attached
                            </div> 
                        @endif
                        <!-- Button -->
                    </div>
                </div>
            @endif
        @endif

        <div class="input-group">
            <label>Requested By:</label>
            <p>{{ $mode === 'view' ? $requestEntry->requested_by : Auth::user()->name }}</p>
        </div>

        <!-- Form Actions -->
        @if($module == 'requestor')
            <div class="flex flex-col">
                @if($mode == 'create')
                    <div x-show="showAction" class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                        <button type="button" @click="validateBeforeModal('submit')" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit to Head</button>
                        <button type="button" @click="modalTarget = 'savedraft'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Save as Draft</button>
                        <button type="button" @click="resetForm(); showModal = false" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
                    </div>
                @endif

                @if($mode == 'view')
                    @if($requestEntry->request_status == 'Draft')
                        <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                            <button type="button" @click="validateBeforeModal('submitdraft')" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit to Head</button>
                            <button type="button" @click="modalTarget = 'deletedraft'; showModal = true" class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Delete Draft</button>
                        </div>
                    @endif

                    @if($requestEntry->request_status == 'Returned to Requestor')
                        <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                            <button x-show="showAction" type="button" @click="validateBeforeModal('resubmit')" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Resubmit to Head</button>
                            <button type="button" @click="modalTarget = 'withdraw'; showModal = true" class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Withdraw Request</button>
                        </div>
                    @endif                        
                @endif
            </div>  
        @endif       

        @if($module == 'division_head')
            <div class="flex flex-col">
                @if($requestEntry->request_status == 'For Head Approval')
                    <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                        <button type="button" @click="modalTarget = 'approverequest'; showModal = true" class="border border-3 border-green-600 bg-green-600 text-white hover:bg-green-800 px-4 py-2">Approve Request</button>
                        <button type="button" @click="modalTarget = 'rejectrequest'; showModal = true" class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Reject Request</button>
                        <button type="button" @click="modalTarget = 'returnedhead'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button>
                    </div>
                @endif
            </div>                
        @endif

        @if($module == 'hr_preparer')
            <div class="flex flex-col">
                @if($requestEntry->request_status == 'For HR Prep')
                    <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                        <button type="button" @click="modalTarget = 'returnedhr'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button>
                    </div>                   
                @endif
            </div>  
        @endif


        <!-- Overlay (instant) -->
        <div x-show="showModal" class="fixed inset-0 bg-black/50 z-40"></div>
        
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
            <div class="bg-white p-6 rounded-lg shadow-lg w-md z-10">
                <h2 class="text-xl font-semibold mb-4" x-text="modalConfig[modalTarget]?.header"></h2>
                <p class="mb-6" x-show="!modalConfig[modalTarget]?.needsInput" x-text="modalConfig[modalTarget]?.message"></p>

                <!-- For input type -->
                <template x-if="modalConfig[modalTarget]?.needsInput">
                    <div class="flex flex-col gap-3 mb-5">
                        <div class="input-group">
                            <label for="header">Return Reason :</label>
                            <select name="header" wire:model="header" required>
                                <option value="">Select reason</option>
                                <option value="Incomplete Employee Details">Incomplete Employee Details</option>
                                <option value="Missing Supporting Documents">Missing Supporting Documents</option>
                                <option value="Incorrect Type of Action">Incorrect Type of Action</option>
                                <option value="Unclear or Insufficient Justification">Unclear or Insufficient Justification</option>
                                <option value="Other">Other (Please Specify)</option>
                            </select>
                        </div>

                        <!-- Show this input if "Other" is selected -->
                        <div class="input-group" x-show="$wire.header === 'Other'">
                            <label>Custom Reason :</label>
                            <input type="text" class="w-full" placeholder="Type your reason" wire:model="customHeader">
                        </div>

                        <div class="input-group">
                            <label>Details :</label>
                            <textarea class="w-full h-24 resize-none" placeholder="(Optional)" wire:model="body"></textarea>
                        </div>
                    </div>
                </template>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                    <button type="button" @click="showModal = false; $wire[modalConfig[modalTarget]?.action](); resetForm()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                </div>
            </div>

        </div>
    </div>
</form>
