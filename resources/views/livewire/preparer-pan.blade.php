
<section class="content-block" id="content-block-2">
    <h1 class="text-[22px]">PAN Preparation Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full" id="pan-form-container"
        x-data = "{
            showModal: false,
            showAction: false,

            modalTarget: '',
            modalConfig: {
                submit: {
                    header: 'Send for Division Head Confirmation',
                    message: 'Are you sure you want to forward this prepared PAN to the Division Head for confirmation?',
                    action: 'submitPan',
                    needsInput: false,
                    needsFormData: true
                },

                resubmit: {
                    header: 'Send for Division Head Confirmation',
                    message: 'Are you sure you want to resubmit this prepared PAN to the Division Head for confirmation?',
                    action: 'resubmitPan',
                    needsInput: false,
                    needsFormData: true
                },

                confirmpan: {
                    header: 'Confirm PAN Form',
                    message: 'This PAN form will be sent to the HR Approver for review. Are you sure you want to proceed?',
                    action: 'confirmPan',
                    needsInput: false,
                    needsFormData: false
                },

                disputeHead: {
                    header: 'Flag for Resolution',
                    action: 'disputeHead',
                    needsInput: true
                },

                approvehr: {
                    header: 'Approve PAN',
                    message: 'Do you want to approve this PAN and forward it to the Final Approver?',
                    action: 'approveHr',
                    needsInput: false,
                    needsFormData: false
                },

                rejecthr: {
                    header: 'Reject PAN',
                    message: 'Are you sure you want to reject this PAN? It will be returned to Requestor for correction.',
                    action: 'rejectHr',
                    needsInput: false,
                    needsFormData: false
                },

                approvefinal: {
                    header: 'Final Approval',
                    message: 'Do you want to give final approval to this PAN?',
                    action: 'approveFinal',
                    needsInput: false,
                    needsFormData: false
                },

                rejectfinal: {
                    header: 'Reject Final Approval',
                    message: 'Are you sure you want to reject this PAN? It will be returned to Requestor for further review.',
                    action: 'rejectFinal',
                    needsInput: false,
                    needsFormData: false
                },
            },

            getFields() {
                return [
                    this.$refs.date_hired, 
                    this.$refs.employment_status, 
                    this.$refs.division, 
                    this.$refs.date_of_effectivity
                ];
            },

            checkEmptyFields() {
                return window.panForm.hasEmptyFromOrTo();
            },

            validateBeforeModal(action) {
                // Highlight required static fields
                let hasEmptyStatic = false;

                this.getFields().forEach((field) => {
                    if (field.value.trim() === '') {
                        field.classList.add('!border-red-500');
                        hasEmptyStatic = true;
                    } else {
                        field.classList.remove('!border-red-500');
                    }
                });

                if (hasEmptyStatic) return false; // Stop if initial fields are empty

                // Now check dynamic fields
                let hasEmptyDynamic = this.checkEmptyFields();
                if (hasEmptyDynamic) return false; // Stop if dynamic fields are empty
            
                // All good, open modal
                this.modalTarget = action;
                this.showModal = true;
                return true; // allow $wire call
            }
        }"
    >

        <!-- Input Fields -->
        <div class="input-fields grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="input-group">
                <label for="date_hired">Date Hired:</label>
                <input id="date_hired" type="date" class="form-input" wire:model="date_hired" x-ref="date_hired" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="employment_status">Employment Status:</label>
                <input id="employment_status" type="text" class="form-input" wire:model="employment_status" x-ref="employment_status" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="division">Division:</label>
                <input id="division" type="text" class="form-input" wire:model="division" x-ref="division" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="date_of_effectivity">Date of Effectivity:</label>
                <input id="date_of_effectivity" type="date" class="form-input" wire:model="date_of_effectivity" x-ref="date_of_effectivity" {{$isDisabled ? 'Readonly' : '' }} />
            </div>
        </div>

        <!-- Action Reference Table -->
        <div class="table-group w-full flex flex-col gap-3">
            <label class="text-[18px] mb-2">Action Reference Table:</label>

            <div class="overflow-hidden rounded-md border-2 border-gray-300">
                <table class="w-full table-fixed border-separate border-spacing-0">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/3 text-center py-3">From</th>
                            <th class="w-1/3 text-center py-3">Action Reference</th>
                            <th class="w-1/3 text-center py-3">To</th>
                        </tr>
                    </thead>
                    <tbody id="pan-tbody">

                        @if($mode == "view")
                            @foreach($referenceTableData as $item)
                                <tr>
                                    <td class="border-t-2 border-gray-300">
                                        <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" value="{{$item['from']}}" Readonly />
                                    </td>
                                    <td class="border-t-2 border-gray-300 text-center capitalize">
                                        @php
                                            $labels = [
                                                'place'    => 'Place of Assignment',
                                                'head'     => 'Immediate Head',
                                                'joblevel' => 'Job Level',
                                            ];
                                        @endphp

                                        {{ $labels[$item['field']] ?? $item['field'] }}
                                    </td>
                                    <td class="border-t-2 border-gray-300">
                                        <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" value="{{$item['to']}}" Readonly />
                                    </td>
                                </tr>                                
                            @endforeach
                        @endif

                        <!-- Static rows will be generated by JS -->
                        <!-- Dynamic allowance rows will be generated by JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remarks -->
        <div class="input-group">
            <label>Remarks and Other Consideration:</label>
            <textarea class="w-full h-30 resize-none" wire:model="remarks" {{$isDisabled ? 'Disabled' : ''}}></textarea>
        </div>

        <div class="flex gap-20">
            <!-- Prepared By -->
            <div class="input-group">
                <label>{{$requestEntry->request_status == 'For HR Prep' ? 'Being' : ''}} Prepared By:</label>
                <p>{{ $mode === 'view' ? $requestEntry->requested_by : Auth::user()->name }}</p>
            </div>
            
            @if($requestEntry->request_status == 'Approved' || $module == 'final_approver')
                <!-- Prepared By -->
                <div class="input-group">
                    <label>{{$requestEntry->request_status != 'Approved' ? 'Being' : ''}} Approved By:</label>
                    <p>{{ $mode === 'view' ? $requestEntry->requested_by : Auth::user()->name }}</p>
                </div> 
            @endif           
        </div>

        @if($module == 'hr_preparer')

            <!-- HR Preparer Actions: -->
            @if($requestEntry->request_status == 'For HR Prep')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="validateBeforeModal('submit')" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit for Confirmation</button>
                <button type="button" @click="resetForm()" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
            </div>
            @endif
            
            @if($requestEntry->request_status == 'For Resolution')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="validateBeforeModal('resubmit')" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Resubmit for Confirmation</button>
                <button type="button" @click="resetForm()" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
            </div>
            @endif
        
        @endif
        
        @if($module == 'division_head')

            <!-- Division Head Actions: -->
            @if($requestEntry->request_status == 'For Confirmation')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'confirmpan'; showModal = true " class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Confirm PAN Form</button>
                <button type="button" @click="modalTarget = 'disputeHead'; showModal = true " class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Flag for Resolution</button>
            </div>
            @endif

        @endif

        @if($module == 'hr_approver')

            <!-- HR Approver Actions: -->
            @if($requestEntry->request_status == 'For HR Approval')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'approvehr'; showModal = true" class="border border-3 border-green-600 bg-green-600 text-white hover:bg-green-800 px-4 py-2">Approve Request</button>
                <button type="button" @click="modalTarget = 'rejecthr'; showModal = true" class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Reject Request</button>
            </div>
            @elseif($requestEntry->request_status == 'Approved')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-800 px-4 py-2" onclick="window.location.href='/print-view?requestID={{ encrypt($requestID) }}'"><i class="fa-solid fa-print"></i> Print Request</button>
            </div>
            @endif

        @endif

        @if($module == 'final_approver')

            <!-- Final Approver Actions: -->
            @if($requestEntry->request_status == 'For Final Approval')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'approvefinal'; showModal = true" class="border border-3 border-green-600 bg-green-600 text-white hover:bg-green-800 px-4 py-2">Approve Request</button>
                <button type="button" @click="modalTarget = 'rejectfinal'; showModal = true" class="border border-3 border-red-600 bg-red-600 text-white hover:bg-red-800 px-4 py-2">Reject Request</button>
            </div>
            @endif
        
        @endif

        <!-- Overlay (instant) -->
        <div x-show="showModal" class="fixed inset-0 bg-black/50 z-50"></div>

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
                            <label for="header">Dispute Subject :</label>
                            <!-- <input type="text" name="header" wire:model="header" required> -->
                            <select name="header" wire:model="header" required>
                                <option value="">Select subject</option>
                                <option value="Incorrect Salary/Allowance Adjustment">Incorrect Salary/Allowance Adjustment</option>
                                <option value="Wrong Effectivity Date">Wrong Effectivity Date</option>
                                <option value="Position/Job Level Mismatch">Position/Job Level Mismatch</option>
                                <option value="Unjustified Action Type">Unjustified Action Type</option>
                                <option value="Incorrect Division or Assignment">Incorrect Division or Assignment</option>
                                <option value="Budgetary/Approval Concerns">Budgetary/Approval Concerns</option>
                                <option value="Policy Compliance Concerns">Policy Compliance Concerns</option>
                                <option value="Other">Other (Specify)</option>
                            </select>
                        </div>

                        <!-- Show this input if "Other" is selected -->
                        <div class="input-group" x-show="$wire.header === 'Other'">
                            <label>Custom Reason :</label>
                            <input type="text" class="w-full" placeholder="Type your reason" wire:model="customHeader">
                        </div>

                        <div class="input-group">
                            <label>Details :</label>
                            <textarea class="w-full h-24 resize-none" wire:model="body" required></textarea>
                        </div>
                    </div>
                </template>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                    <button type="button" 
                        @click="
                            showModal = false;
                            const config = modalConfig[modalTarget];
                            if (config?.needsFormData) {
                                $wire[config.action](window.panForm.getFormData());
                            } else {
                                $wire[config.action]();
                            }
                            resetForm();
                        "
                     class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                </div>
            </div>

        </div>

        <!-- Debug Section -->
        <!-- <div class="mt-8 p-4 bg-gray-100 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">Current Form Data</h3>
            <button id="debug-btn" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 mr-2">
                Show Data
            </button>
            <button id="reset-btn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Reset Form
            </button>
            <pre id="debug-output" class="mt-4 text-xs bg-white p-2 rounded overflow-auto hidden"></pre>
        </div> -->

    </div>
    <style>
        .hidden{
            display: none;
        }
    </style>
</section>


@if($mode == "create")
<script>
    class PANForm {
        constructor() {
            this.staticFields = [
                { field: 'section', label: 'Section', from: '', to: '' },
                { field: 'place', label: 'Place of Assignment', from: '', to: '' },
                { field: 'head', label: 'Immediate Head', from: '', to: '' },
                { field: 'position', label: 'Position', from: '', to: '' },
                { field: 'joblevel', label: 'Job Level', from: '', to: '' },
                { field: 'basic', label: 'Basic', from: '', to: '' }
            ];

            this.allowances = [];

            this.allOptions = [
                'Communication Allowance',
                'Meal Allowance',
                'Living Allowance',
                'Transportation Allowance',
                'Clothing Allowance',
                'Fuel Allowance',
                'Management Allowance',
                'Developmental Assignments',
                'Professional Allowance',
                'Interim Allowance',
                'Training Allowance',
                'Mancom Allowance'
            ];

            this.init();
        }

        init() {
            // Load initial data (simulating Livewire data)
            // this.loadInitialData();
            
            // Render the table
            this.renderTable();
            
            // Setup event listeners
            this.setupEventListeners();
        }

        loadInitialData() {
            // Simulate loading data from Livewire/database
            this.staticFields = [
                { field: 'section', label: 'Section', from: 'HR Department', to: 'Finance Department' },
                { field: 'place', label: 'Place of Assignment', from: 'Quezon City Office', to: 'Manila Office' },
                { field: 'head', label: 'Immediate Head', from: 'Jane Doe', to: 'John Smith' },
                { field: 'position', label: 'Position', from: 'Junior Analyst', to: 'Senior Analyst' },
                { field: 'joblevel', label: 'Job Level', from: 'Level 2', to: 'Level 3' },
                { field: 'basic', label: 'Basic', from: '35000', to: '45000' }
            ];

            this.allowances = [
                { id: 1, value: 'Clothing Allowance', from: '1500', to: '2000' },
                { id: 2, value: 'Communication Allowance', from: '2500', to: '3000' },
                { id: 3, value: 'Meal Allowance', from: '4000', to: '5000' }
            ];

            // Load form fields
            document.getElementById('date_hired').value = '2023-01-15';
            document.getElementById('employment_status').value = 'Regular';
            document.getElementById('division').value = 'IT Department';
            document.getElementById('date_of_effectivity').value = '2024-01-01';
            document.getElementById('remarks').value = 'Promotion due to excellent performance';
        }

        renderTable() {
            const tbody = document.getElementById('pan-tbody');
            tbody.innerHTML = '';

            // Render static rows
            this.staticFields.forEach(field => {
                const row = this.createStaticRow(field);
                tbody.appendChild(row);
            });

            // Render allowance rows
            this.allowances.forEach((allowance, index) => {
                const row = this.createAllowanceRow(allowance, index);
                tbody.appendChild(row);
            });

            // Add "Add Allowance" row
            const addRow = this.createAddAllowanceRow();
            tbody.appendChild(addRow);
        }

        createStaticRow(field) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border-t-2 border-gray-300">
                    <input 
                        type="text" 
                        class="w-full border-none focus:ring-0 text-center outline-none" 
                        value="${field.from}"
                        data-field="${field.field}"
                        data-type="from"
                    />
                </td>
                <td class="border-t-2 border-gray-300 text-center font-medium relative">
                    ${field.label}
                    <i class="fa-solid fa-circle-exclamation text-red-400 absolute right-10 top-[14px] hidden" data-static-warning="${field.field}"></i>
                </td>
                <td class="border-t-2 border-gray-300">
                    <input 
                        type="text" 
                        class="w-full border-none focus:ring-0 text-center outline-none" 
                        value="${field.to}"
                        data-field="${field.field}"
                        data-type="to"
                    />
                </td>
            `;

            // Add event listeners for static fields
            const inputs = row.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('input', (e) => {
                    const fieldName = e.target.dataset.field;
                    const type = e.target.dataset.type;
                    const fieldIndex = this.staticFields.findIndex(f => f.field === fieldName);
                    if (fieldIndex !== -1) {
                        this.staticFields[fieldIndex][type] = e.target.value;
                    }
                });
            });

            return row;
        }

        createAllowanceRow(allowance, index) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border-t-2 border-gray-300">
                    <input 
                        type="text" 
                        class="w-full border-none focus:ring-0 text-center outline-none" 
                        value="${allowance.from}"
                        data-allowance-index="${index}"
                        data-type="from"
                    />
                </td>
                <td class="border-t-2 relative border-gray-300 text-center font-medium">
                    <i class="fa-regular fa-trash-can absolute left-[10px] top-[15px] cursor-pointer text-red-600 hover:scale-110" data-remove-index="${index}"></i>
                    <select 
                        class="w-full border-none focus:ring-0 text-center outline-none p-0"
                        data-allowance-index="${index}"
                        data-type="value"
                    >
                        <option value="">Select Allowance</option>
                        ${this.getAvailableOptions(index).map(opt => 
                            `<option value="${opt}" ${allowance.value === opt ? 'selected' : ''}>${opt}</option>`
                        ).join('')}
                    </select>
                    <i class="fa-solid fa-circle-exclamation text-red-400 absolute right-10 top-[14px] hidden" data-allowance-warning="${index}"></i>
                </td>
                <td class="border-t-2 border-gray-300">
                    <input 
                        type="text" 
                        class="w-full border-none focus:ring-0 text-center outline-none" 
                        value="${allowance.to}"
                        data-allowance-index="${index}"
                        data-type="to"
                    />
                </td>
            `;

            // Add event listeners for allowance fields
            const inputs = row.querySelectorAll('input, select');
            inputs.forEach(input => {
                const eventType = input.tagName === 'SELECT' ? 'change' : 'input';
                input.addEventListener(eventType, (e) => {
                    const allowanceIndex = parseInt(e.target.dataset.allowanceIndex);
                    const type = e.target.dataset.type;
                    
                    if (this.allowances[allowanceIndex]) {
                        this.allowances[allowanceIndex][type] = e.target.value;
                        
                        // If it's a select change, re-render to update available options
                        if (type === 'value') {
                            this.renderTable();
                        }
                    }
                });
            });

            // Add remove button listener
            const removeBtn = row.querySelector('[data-remove-index]');
            removeBtn.addEventListener('click', () => {
                this.removeAllowance(index);
            });

            return row;
        }

        hasEmptyFromOrTo() {
            let hasEmpty = false;

            // Validate static fields
            this.staticFields.forEach((field) => {
                const rowIcon = document.querySelector(`[data-static-warning="${field.field}"]`);
                const isInvalid = !field.from || !field.to; // FIXED
                if (rowIcon) rowIcon.classList.toggle('hidden', !isInvalid); // FIXED
                if (isInvalid) hasEmpty = true;
            });

            // Validate allowances (including missing "value")
            this.allowances.forEach((allowance, index) => {
                const rowIcon = document.querySelector(`[data-allowance-warning="${index}"]`);
                const isInvalid = !allowance.value || !allowance.from || !allowance.to;
                if (rowIcon) rowIcon.classList.toggle('hidden', !isInvalid);
                if (isInvalid) hasEmpty = true;
            });

            return hasEmpty;
        }

        createAddAllowanceRow() {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border-t-2 border-gray-300">
                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none py-3" disabled/>
                </td>
                <td class="border-t-2 border-gray-300 text-center font-medium py-3">
                    <div class="text-blue-500 cursor-pointer hover:scale-105" id="add-allowance-table-btn">+ Add Allowance</div>
                </td>
                <td class="border-t-2 border-gray-300">
                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none py-3" disabled/>
                </td>
            `;

            // Add click listener for add button
            const addBtn = row.querySelector('#add-allowance-table-btn');
            addBtn.addEventListener('click', () => {
                this.addAllowance();
            });

            return row;
        }

        getAvailableOptions(index) {
            const usedValues = this.allowances
                .map(a => a.value)
                .filter((val, i) => val && val.trim() !== '' && i !== index);
            
            return this.allOptions.filter(opt => !usedValues.includes(opt));
        }

        addAllowance() {
            this.allowances.push({
                id: Date.now() + Math.random(),
                value: '',
                from: '',
                to: ''
            });
            this.renderTable();
        }

        removeAllowance(index) {
            this.allowances.splice(index, 1);
            this.renderTable();
        }

        setupEventListeners() {
            // // Add allowance button
            // document.getElementById('add-allowance-btn').addEventListener('click', () => {
            //     this.addAllowance();
            // });

            // Debug button
            // document.getElementById('debug-btn').addEventListener('click', () => {
            //     // this.showDebugData();
            //     this.hasEmptyFromOrTo();
            // });

            // // Reset button
            // document.getElementById('reset-btn').addEventListener('click', () => {
            //     this.resetForm();
            // });
        }

        showDebugData() {
            const debugOutput = document.getElementById('debug-output');
            const data = {
                staticFields: this.staticFields,
                allowances: this.allowances,
                formFields: {
                    date_hired: document.getElementById('date_hired').value,
                    employment_status: document.getElementById('employment_status').value,
                    division: document.getElementById('division').value,
                    date_of_effectivity: document.getElementById('date_of_effectivity').value,
                    remarks: document.getElementById('remarks').value
                }
            };
            
            debugOutput.textContent = JSON.stringify(data, null, 2);
            debugOutput.classList.toggle('hidden');
        }

        resetForm() {
            // Reset form fields
            document.getElementById('date_hired').value = '';
            document.getElementById('employment_status').value = '';
            document.getElementById('division').value = '';
            document.getElementById('date_of_effectivity').value = '';
            document.getElementById('remarks').value = '';

            // Reset data
            this.staticFields = this.staticFields.map(field => ({
                ...field,
                from: '',
                to: ''
            }));
            
            this.allowances = [];
            
            // Re-render
            this.renderTable();
        }

        // Method to get form data in Livewire format
        getFormData() {
            const formData = [];
            
            // Add static fields
            this.staticFields.forEach(field => {
                if (field.from || field.to) {
                    formData.push({
                        field: field.field,
                        from: field.from,
                        to: field.to
                    });
                }
            });

            // Add allowances
            this.allowances.forEach(allowance => {
                if (allowance.value && (allowance.from || allowance.to)) {
                    formData.push({
                        field: allowance.value,
                        from: allowance.from,
                        to: allowance.to
                    });
                }
            });

            return formData;
        }

        // On save button click
        // const formData = window.panForm.getFormData();
        // @this.call('saveFormData', formData);

        // Method to load data from Livewire format
        loadFromLivewireData(data) {
            // Reset current data
            this.staticFields.forEach(field => {
                field.from = '';
                field.to = '';
            });
            this.allowances = [];

            data.forEach(item => {
                // Check if it's a static field
                const staticField = this.staticFields.find(sf => sf.field === item.field);
                if (staticField) {
                    staticField.from = item.from;
                    staticField.to = item.to;
                } else {
                    // It's an allowance field
                    this.allowances.push({
                        id: Date.now() + Math.random(),
                        value: item.field,
                        from: item.from,
                        to: item.to
                    });
                }
            });

            // Re-render the table
            this.renderTable();
        }
    }

    // Initialize the form when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Get the data from Livewire
        const referenceTableData = @json($referenceTableData);
        
        // Initialize the form
        window.panForm = new PANForm();
        
        // Load the actual data
        if(referenceTableData){
            window.panForm.loadFromLivewireData(referenceTableData);
        }
        
    });
</script>
@endif