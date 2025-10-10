
<section class="content-block relative overflow-hidden" id="content-block-2"
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

            servehr: {
                header: 'Mark as Served',
                message: 'Are you sure you want to mark this request as Served? This means the PAN has been handed over or acknowledged by the employee.',
                action: 'serveHr',
                needsInput: false,
                needsFormData: false
            },

            filehr: {
                header: 'Mark as Served',
                message: 'Are you sure you want to mark this request as Filed? This will indicate the PAN has been archived into the employee’s 201 file.',
                action: 'fileHr',
                needsInput: false,
                needsFormData: false
            },

            rejecthr: {
                header: 'Return PAN',
                message: 'Are you sure you want to reject this PAN? It will be returned to HR for correction.',
                action: 'rejectHr',
                needsInput: true,
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
                header: 'Return PAN',
                message: 'Are you sure you want to reject this PAN? It will be returned to Requestor for further review.',
                action: 'rejectFinal',
                needsInput: true,
                needsFormData: false
            },

            conmanila: {
                header: 'Set Confidentiality Tag',
                message: 'Are you sure you want to mark this PAN request as confidential under Manila?',
                action: 'conManila',
                needsInput: false,
                needsFormData: false
            },

            contarlac: {
                header: 'Set Confidentiality Tag',
                message: 'Are you sure you want to mark this PAN request as confidential under Tarlac?',
                action: 'conTarlac',
                needsInput: false,
                needsFormData: false
            },

            updatepan: {
                header: 'Update Employee’s PAN',
                message: 'Are you sure you want to update this employee’s PAN? This action will create a new PAN request entry',
                action: 'updatePan',
                needsTrigger: true,
                needsFormData: false
            }       


        },

        getFields() {
            return [
                this.$refs.date_hired,
                this.$refs.employment_status,
                this.$refs.division,
                this.$refs.date_of_effectivity, // validate this display input
                this.$refs.wage_no
            ].filter(Boolean);
        },

        init() {
            this.getFields().forEach(field => {
                ['input', 'change'].forEach(event =>
                    field.addEventListener(event, () => this.checkFields())
                );
            });
        },

        checkFields() {
            this.showAction = this.getFields().some(f => f.value?.trim());
        },

        // 👇 bring this back (dynamic allowance validation)
        checkEmptyFields() {
            return window.panForm?.hasEmptyFromOrTo?.() || false;
        },

        validateBeforeModal(action) {
            let hasEmptyStatic = false;

            this.getFields().forEach((field) => {
                if (!field.value.trim()) {
                    field.classList.add('!border-red-500');
                    hasEmptyStatic = true;
                } else {
                    field.classList.remove('!border-red-500');
                }
            });

            if (hasEmptyStatic) return false;

            // Now check dynamic fields (allowances)
            let hasEmptyDynamic = this.checkEmptyFields();
            if (hasEmptyDynamic) return false;

            this.modalTarget = action;
            this.showModal = true;
            return true;
        },

        date_hired: @entangle('date_hired'),
        date_of_effectivity: @entangle('date_of_effectivity'),
        open: false
    }"
>

    @if($module == 'hr_preparer')
        @if(is_null($requestEntry->confidentiality))
            <div class="absolute inset-0 bg-black/30 z-40 flex gap-3 items-end justify-end p-10">
                <i class="fa-solid fa-lock absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-6xl"></i>
                <button class="border border-3 border-purple-500 bg-purple-500 rounded-md cursor-pointer text-white hover:bg-purple-600 px-4 py-2" @click="modalTarget = 'conmanila'; showModal = true">
                    Tag as Manila
                </button>

                <button class="border border-3 border-blue-500 bg-blue-500 rounded-md cursor-pointer text-white hover:bg-blue-600 px-4 py-2" @click="modalTarget = 'contarlac'; showModal = true">
                    Tag as Tarlac
                </button>
            </div>
        @endif
    @endif


    <h1 class="text-[22px]">PAN Preparation Form</h1>
    <div class="absolute top-10 right-10 font-mono font-semibold text-gray-600 bg-gray-100 px-4 py-1 rounded-md">
        {{$requestEntry->request_no}}
    </div>

    <div class="form-container relative flex flex-col gap-5 h-full" id="pan-form-container">

        <!-- Input Fields -->
        <div class="input-fields grid gap-4 sm:grid-cols-1 {{$requestEntry->type_of_action == 'Wage Order' ? 'md:grid-cols-5' : 'md:grid-cols-4'}}" >
            <div class="input-group">
                <label for="date_hired">Date Hired:</label>
                <input id="date_hired" type="date" class="form-input" wire:model="date_hired" x-ref="date_hired" x-model="date_hired" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="employment_status">Employment Status:</label>
                <select id="employment_status" type="text" class="form-input" wire:model="employment_status" x-ref="employment_status" {{$isDisabled ? 'Disabled' : '' }}>
                    <option value=""></option>
                    <option value="Probationary">Probationary</option>
                    <option value="Regular">Regular</option>
                    <option value="Project-Based">Project-Based</option>
                    <option value="Fixed-Term">Fixed-Term</option>
                    <option value="Casual">Casual</option> 
                    <option value="Part-Time">Part-Time</option> 
                    <option value="Seasonal">Seasonal</option> 
                </select>
            </div>

            <div class="input-group">
                <label for="division">Division/Department:</label>
                <input id="division" type="text" class="form-input" wire:model="division" x-ref="division" readonly/>
            </div>
            
            @if($mode == "create")
                <div class="relative">
                    <div class="input-group">
                        <label for="date_of_effectivity">Date of Effectivity:</label>

                        <!-- Display input (validated by Alpine) -->
                        <input id="date_of_effectivity"
                            x-ref="date_of_effectivity"
                            type="text"
                            class="form-input cursor-pointer"
                            :value="($wire.date_of_effectivity_from && $wire.date_of_effectivity_to) 
                                        ? $wire.date_of_effectivity_from + ' - ' + $wire.date_of_effectivity_to 
                                        : ''"
                            readonly
                            @click="open = !open" />

                        <!-- Dropdown -->
                        <div x-show="open" @click.outside="open = false"
                            class="absolute top-[75px] mt-2 bg-white border p-3 rounded shadow w-70 z-50">
                            
                            <label class="block text-sm mb-1">From:</label>
                            <input type="date" 
                                wire:model="date_of_effectivity_from"
                                class="border rounded px-2 py-1 w-full mb-2"
                                :min="$wire.date_hired"
                            >

                            <label class="block text-sm mb-1">To:</label>
                            <input type="date" 
                                wire:model="date_of_effectivity_to"
                                class="border rounded px-2 py-1 w-full"
                                :min="$wire.date_of_effectivity_from || $wire.date_hired"
                            >
                        </div>
                    </div>
                </div>
            @else 
                <div class="input-group">
                    <label for="date_of_effectivity">Date of Effectivity:</label>
                    <input id="date_of_effectivity" type="text" x-ref="date_of_effectivity" value="{{$panEntry->doe_from->format('m/d/Y') }} - {{$panEntry->doe_to->format('m/d/Y') }}" {{$isDisabled ? 'Readonly' : '' }} />
                </div>
            @endif

            @if($requestEntry->type_of_action == 'Wage Order')
                <div class="input-group">
                    <label for="wage_no">Wage Order No:</label>
                    <input type="text" id="wage_no" wire:model="wage_no" x-ref="wage_no" {{$isDisabled ? 'readonly' : '' }} />
                </div>
            @endif
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

        @if($module == 'hr_preparer' && $mode === 'create')
            @if($recentRequestCompleted && !is_null($requestEntry->confidentiality))
                <div class="flex items-center mb-2 text-sm text-gray-600 ml-2">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-arrow-turn-up fa-rotate-90 text-blue-500"></i>
                        Pre-generated from: 
                        <a href="/hrpreparer-view?requestID={{ encrypt($recentRequestCompleted->id) }}" target="_blank" class="font-mono font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md cursor-pointer hover:underline">
                            {{$recentRequestCompleted->request_no}}.
                        </a>
                        <a href="/hrpreparer/employeerecord-view?requestID={{ encrypt($recentRequestCompleted->employee_id) }}" target="_blank" class="text-blue-600 underline font-semibold">See more</a>
                    </span>
                </div>
            @endif
        @endif

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
            
                @if(!is_null($requestEntry->confidentiality) && $recentPanCompleted)
                   <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                @else 
                    <div x-show="showAction" class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                @endif
                
                    <button type="button" @click="validateBeforeModal('submit')" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2">Submit for Confirmation</button>
                    @if($requestEntry->confidentiality == 'manila') 
                        <button class="border border-3 border-sky-500 bg-sky-500 rounded-md cursor-pointer text-white hover:bg-sky-600 px-4 py-2" @click="modalTarget = 'contarlac'; showModal = true">
                            Tag as Tarlac
                        </button>                                            
                    @else
                        <button class="border border-3 border-red-500 bg-red-500 rounded-md cursor-pointer text-white hover:bg-red-600 px-4 py-2" @click="modalTarget = 'conmanila'; showModal = true">
                            Tag as Manila
                        </button>                            
                    @endif
                    <button type="button" @click="resetForm()" class="border-3 border-gray-400 text-gray-700 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
                </div>
            @endif
            
            @if($requestEntry->request_status == 'For Resolution')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="validateBeforeModal('resubmit')" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2">Resubmit for Confirmation</button>
                <button type="button" @click="resetForm()" class="border-3 border-gray-400 text-gray-700 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
            </div>
            @elseif($requestEntry->request_status == 'Approved')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'servehr'; showModal = true" class="border border-3 border-lime-600 bg-lime-600 text-white hover:bg-lime-700 px-4 py-2">Mark as Served</button>
                <button type="button" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2" onclick="window.location.href='/print-view?requestID={{ encrypt($requestID) }}'"><i class="fa-solid fa-print"></i> Print</button>
            </div>
            @elseif($requestEntry->request_status == 'Served')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'filehr'; showModal = true" class="border border-3 border-emerald-600 bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-2">Mark as Filed</button>
                <button type="button" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2" onclick="window.location.href='/print-view?requestID={{ encrypt($requestID) }}'"><i class="fa-solid fa-print"></i> Print</button>
            </div>
            @elseif($requestEntry->request_status == 'Filed')
            <div class="form-buttons bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                @if($canUpdate)
                    <!-- Allow update -->
                    <button type="button"
                        @click="modalTarget = 'updatepan'; showModal = true"
                        class="border border-3 border-emerald-600 bg-emerald-600 text-white hover:bg-emerald-700 px-4 py-2">
                        Update PAN
                    </button>
                @elseif($isRecentRequestCompleted && $hasNewerOngoing)
                    <!-- Ongoing request exists -->
                    <p class="absolute top-[40px] text-xs text-red-600 whitespace-nowrap">
                        Cannot update — there’s an 
                        <a href="/hrpreparer-view?requestID={{ encrypt($newestRequest->id) }}" class="underline font-bold">
                            ongoing
                        </a> 
                        request for this employee.
                    </p>
                    <button type="button"
                        class="border border-3 border-gray-600 bg-gray-600 text-white px-4 py-2 cursor-not-allowed"
                        disabled>
                        Update PAN
                    </button>
                @endif
                <button type="button" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2" onclick="window.location.href='/print-view?requestID={{ encrypt($requestID) }}'"><i class="fa-solid fa-print"></i> Print</button>
            </div>
            @endif
        
        @endif
        
        @if($module == 'division_head')

            <!-- Division Head Actions: -->
            @if($requestEntry->request_status == 'For Confirmation')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'confirmpan'; showModal = true " class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2">Confirm PAN Form</button>
                <button type="button" @click="modalTarget = 'disputeHead'; showModal = true " class="border border-3 border-amber-600 bg-amber-600 text-white hover:bg-amber-700 px-4 py-2">Flag for Resolution</button>
            </div>
            @endif

        @endif

        @if($module == 'hr_approver')

            <!-- HR Approver Actions: -->
            @if($requestEntry->request_status == 'For HR Approval')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'approvehr'; showModal = true" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2">Approve Request</button>
                <button type="button" @click="modalTarget = 'rejecthr'; showModal = true" class="border border-3 border-amber-600 bg-amber-600 text-white hover:bg-amber-700 px-4 py-2">Return Request</button>
            </div>
            @elseif($requestEntry->request_status == 'Approved')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" class="border border-3 border-blue-600 bg-blue-600 text-white hover:bg-blue-700 px-4 py-2" onclick="window.location.href='/print-view?requestID={{ encrypt($requestID) }}'"><i class="fa-solid fa-print"></i> Print</button>
            </div>
            @endif

        @endif

        @if($module == 'final_approver')

            <!-- Final Approver Actions: -->
            @if($requestEntry->request_status == 'For Final Approval')
            <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                <button type="button" @click="modalTarget = 'approvefinal'; showModal = true" class="border border-3 border-green-600 bg-green-600 text-white hover:bg-green-700 px-4 py-2">Approve Request</button>
                <button type="button" @click="modalTarget = 'rejectfinal'; showModal = true" class="border border-3 border-amber-600 bg-amber-600 text-white hover:bg-amber-700 px-4 py-2">Return Request</button>
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
                            <label for="header"><span class="text-red-600 font-bold">*</span> {{$requestEntry->request_status == 'For Resolution' ? 'Dispute' : 'Return' }} Subject :</label>
                            <!-- <input type="text" name="header" wire:model="header" required> -->
                            <select name="header" wire:model="header" required>
                                <option value="">Select subject </option>
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
                            <label><span class="text-red-600 font-bold">*</span> Custom Reason :</label>
                            <input type="text" class="w-full" placeholder="Type your reason" wire:model="customHeader">
                        </div>

                        <div class="input-group">
                            <label><span class="text-red-600 font-bold">*</span> Details :</label>
                            <textarea class="w-full h-24 resize-none" wire:model="body" required></textarea>
                        </div>
                    </div>
                </template>

                <template x-if="modalConfig[modalTarget]?.needsTrigger">
                    <div class="flex flex-col gap-3 mb-5 mt-[-10px]">
                        <div class="input-group">
                            <label><span class="text-red-600 font-bold">*</span> Type of action:</label>
                            <select name="type_of_action" wire:model="type_of_action" required>
                                <option value="">Select type of action</option>
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

@if($mode == 'create')
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
                // Render the table
                this.renderTable();
                
                // Setup event listeners
                this.setupEventListeners();
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
                    const isInvalid = !field.from || !field.to;
                    if (rowIcon) rowIcon.classList.toggle('hidden', !isInvalid);
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
                // Setup any additional event listeners here
            }

            showDebugData() {
                const debugOutput = document.getElementById('debug-output');
                const data = {
                    staticFields: this.staticFields,
                    allowances: this.allowances,
                    formFields: {
                        date_hired: document.getElementById('date_hired')?.value || '',
                        employment_status: document.getElementById('employment_status')?.value || '',
                        division: document.getElementById('division')?.value || '',
                        date_of_effectivity: document.getElementById('date_of_effectivity')?.value || '',
                        remarks: document.getElementById('remarks')?.value || ''
                    }
                };
                
                debugOutput.textContent = JSON.stringify(data, null, 2);
                debugOutput.classList.toggle('hidden');
            }

            resetForm() {
                // Reset form fields
                const formFields = ['date_hired', 'employment_status', 'division', 'date_of_effectivity', 'remarks'];
                formFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) field.value = '';
                });

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

            // NEW METHOD: Load data from recent PAN (to values become from values)
            loadFromRecentPANData(data) {
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
                        // The "to" value from previous PAN becomes the "from" value in new PAN
                        staticField.from = item.to;
                        staticField.to = ''; // Leave "to" empty for new input
                    } else {
                        // It's an allowance field
                        this.allowances.push({
                            id: Date.now() + Math.random(),
                            value: item.field,
                            from: item.to, // Previous "to" becomes current "from"
                            to: '' // Leave "to" empty for new input
                        });
                    }
                });

                // Re-render the table
                this.renderTable();
            }
        }

        // Initialize the form when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Get the data from Livewire - these variables come from your Blade template
            const confidentiality = @json($requestEntry->confidentiality ?? null);
            const status = @json($requestEntry->request_status);
            const recentPanCompletedData = @json($recentPanCompletedData ?? null);
            const referenceTableData = @json($referenceTableData ?? null);
            
            // Initialize the form
            window.panForm = new PANForm();

            if(confidentiality){
                if(status == 'For HR Prep' && recentPanCompletedData){
                    // Create mode with recent PAN data - "to" values become "from" values
                    window.panForm.loadFromRecentPANData(recentPanCompletedData);            
                } else if (referenceTableData) {
                    // Edit mode or create mode with reference data - use data as-is
                    window.panForm.loadFromLivewireData(referenceTableData);
                }                
            }
        });
    </script>
@endif
