

<form class="h-full {{$mode == 'create' ? 'pb-8' : ''}}" wire:submit.prevent="submitForm">
    <!-- -- Status only in view mode -- -->
    @if($mode === 'view')
        <x-statustag :status-text="$requestEntry->request_status" status-location="Container"/>
    @endif

    <h1 class="text-[22px]">Request Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full" 
        x-data="{
            showModal: false,
            showAction: false,

            formAction: '',
            modalHeader: '',
            modalMessage: '',

            // helper to collect all fields
            get fields(){
                return [
                    this.$refs.employee_name,
                    this.$refs.employee_id,
                    this.$refs.department,
                    this.$refs.type_of_action,
                    this.$refs.justification,
                    this.$refs.supporting_file,
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


            validateBeforeModal(header, message) {

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

                this.showModal = true;
                this.modalHeader = header;
                this.modalMessage = message;

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
                </select>
            </div>
        </div>

        <div class="input-group h-full">
            <label for="justification">Justification:</label>
            <textarea class="resize-none {{$mode == 'view' ? 'h-[200px]' : 'h-full'}}" name="justification" id="justification" class="w-full h-full resize-none" x-ref="justification" wire:model="justification" {{$isDisabled ? 'Readonly' : ''}}></textarea>
        </div>

        @if($isDisabled)
            <div class="file-group flex flex-col gap-2">
                <label for="" class="text-[18px]">Supporting Files:</label>
                <div class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm">
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
        @else
            <div class="file-group flex flex-col gap-2">
                <label for="supporting_file" class="text-[18px]">Supporting File:</label>
                <input name="supporting_file" id="supporting_file" class="block w-full text-sm text-gray-500 border border-1 border-gray-600 rounded-md cursor-pointer bg-gray-50 focus:outline-none" type="file" x-ref="supporting_file" wire:model="supporting_file">
            </div>
        @endif

        <div class="input-group">
            <label>Requested By:</label>
            <p>{{ $mode === 'view' ? $requestEntry->requested_by : 'Iverson Guno' }}</p>
        </div>

        <!-- Buttons -->
        @if($mode == "create")
            <div disabled:="!showAction" x-show="showAction" class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="validateBeforeModal('Confirm Submission', 'Are you sure you want to submit this request for processing?'); formAction = 'submitRequest' " class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit</button>
                <button type="button" @click="showModal = true; formAction = 'saveDraft'; modalHeader = 'Save Draft'; modalMessage = 'Do you want to save this request as a draft?' " class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Save as Draft</button>
                <button type="button" @click="resetForm()" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
            </div>
        @elseif($mode == "view")
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                @if($requestEntry->request_status == "Draft")
                    <button disabled:="!showAction" x-show="showAction" type="button" @click="validateBeforeModal('Confirm Submission', 'Are you sure you want to submit this draft for processing?'); formAction = 'submitDraft' " class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit to HR</button>
                    <button type="button" @click="showModal = true; formAction = 'deleteDraft'; modalHeader = 'Delete Draft'; modalMessage = 'Are you sure you want to delete this draft?' " class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Delete Draft</button>
                @elseif($requestEntry->request_status == "Returned to Requestor")
                    <button disabled:="!showAction" x-show="showAction" type="button" @click="validateBeforeModal('Confirm Resubmission', 'Are you sure you want to resubmit this request for processing?'); formAction = 'resubmitRequest' " class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Resubmit to HR</button>
                    <button type="button" @click="showModal = true; formAction = 'withdrawRequest'; modalHeader = 'Withdraw Request'; modalMessage = 'Are you sure you want to withdraw this request? This action will be recorded.' " class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Withdraw Request</button>
                @endif
            </div>
        @endif

        <!-- Overlay (instant) -->
        <div x-show="showModal" class="fixed inset-0 bg-black/50"></div>

        <!-- Modal box (with transition) -->
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96 z-10">
                <h2 class="text-xl font-semibold mb-4" x-text="modalHeader"></h2>
                <p class="mb-6" x-text="modalMessage"></p>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                    <button type="button" @click="showModal = false; $wire[formAction](); {{$mode == 'create' ? 'resetForm();' : ''}}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</form>