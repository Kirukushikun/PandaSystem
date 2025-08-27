<section class="content-block" id="content-block-2">
    <h1 class="text-[22px]">PAN Preparation Form</h1>

    <div class="form-container relative flex flex-col gap-5 h-full" x-data="panForm()" x-init="init()">
        <!-- Input Fields -->
        <div class="input-fields grid grid-cols md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="input-group">
                <label for="date_hired">Date Hired:</label>
                <input id="date_hired" type="date" x-ref="date_hired" wire:model="date_hired" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="employment_status">Employment Status:</label>
                <input id="employment_status" type="text" x-ref="employment_status" wire:model="employment_status" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="division">Division:</label>
                <input id="division" type="text" x-ref="division" wire:model="division" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
            <div class="input-group">
                <label for="date_of_effectivity">Date of Effectivity:</label>
                <input id="date_of_effectivity" type="date" x-ref="date_of_effectivity" wire:model="date_of_effectivity" {{$isDisabled ? 'Readonly' : '' }}/>
            </div>
        </div>

        <!-- Action Reference Table -->
        <div class="table-group w-full flex flex-col gap-3">
            <label class="text-[18px] mb-2">Action Reference Table:</label>

            <div class="overflow-hidden rounded-md border-2 border-gray-300">
                <table class="w-full table-fixed border-separate border-spacing-0">
                    <thead class="bg-white">
                        <tr>
                            <th class="w-1/3 text-center">From</th>
                            <th class="w-1/3 text-center">Action Reference</th>
                            <th class="w-1/3 text-center">To</th>
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
                        @elseif($mode == "create")
                            <!-- Section Row -->
                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="section_from" wire:model="section_from" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                                <td class="border-t-2 border-gray-300 text-center">
                                    Section
                                    <i class="fa-solid fa-circle-exclamation text-red-400" x-show="validationAttempted && !rows.section.valid"></i>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="section_to" wire:model="section_to" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                            </tr>

                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="place_from" wire:model="place_from" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                                <td class="border-t-2 border-gray-300 text-center">
                                    Place of Assignment
                                    <i class="fa-solid fa-circle-exclamation text-red-400" x-show="validationAttempted && !rows.place.valid"></i>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="place_to" wire:model="place_to" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                            </tr>

                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="head_from" wire:model="head_from" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                                <td class="border-t-2 border-gray-300 text-center">
                                    Immediate Head
                                    <i class="fa-solid fa-circle-exclamation text-red-400" x-show="validationAttempted && !rows.head.valid"></i>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="head_to" wire:model="head_to" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                            </tr>

                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="position_from" wire:model="position_from" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                                <td class="border-t-2 border-gray-300 text-center">
                                    Position
                                    <i class="fa-solid fa-circle-exclamation text-red-400" x-show="validationAttempted && !rows.position.valid"></i>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="position_to" wire:model="position_to" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                            </tr>

                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="joblevel_from" wire:model="joblevel_from" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                                <td class="border-t-2 border-gray-300 text-center">
                                    Job Level
                                    <i class="fa-solid fa-circle-exclamation text-red-400" x-show="validationAttempted && !rows.joblevel.valid"></i>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="joblevel_to" wire:model="joblevel_to" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                            </tr>

                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="basic_from" wire:model="basic_from" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                                <td class="border-t-2 border-gray-300 text-center">
                                    Basic
                                    <i class="fa-solid fa-circle-exclamation text-red-400" x-show="validationAttempted && !rows.basic.valid"></i>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-ref="basic_to" wire:model="basic_to" {{$isDisabled ? 'Disabled' : ''}} />
                                </td>
                            </tr>

                        
                            <!-- Allowance Rows -->
                            <template x-for="(allowance, index) in allowances" :key="index">
                                <tr>
                                    <td class="border-t-2 border-gray-300">
                                        <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-model="allowance.from" @input="checkRows()" :name="'allowances['+index+'][from]'" />
                                    </td>
                                    <td class="border-t-2 relative border-gray-300 text-center font-medium">
                                        <i class="fa-regular fa-trash-can absolute left-[10px] top-[15px] cursor-pointer text-red-600 hover:scale-110" @click="removeAllowance(index)"></i>

                                        <select class="w-full border-none focus:ring-0 text-center outline-none p-0" x-model="allowance.value" @change="checkRows()" :name="'allowances['+index+'][value]'">
                                            <option value="">Select Allowance</option>
                                            <template x-for="opt in getAvailableOptions(index)" :key="opt">
                                                <option x-text="opt"></option>
                                            </template>
                                        </select>

                                        <!-- This will hide once allowance.valid flips true -->
                                        <i class="fa-solid fa-circle-exclamation text-red-400 absolute right-10 top-[14px]" x-show="validationAttempted && !allowance.valid"></i>
                                    </td>
                                    <td class="border-t-2 border-gray-300">
                                        <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" x-model="allowance.to" @input="checkRows()" :name="'allowances['+index+'][to]'" />
                                    </td>
                                </tr>
                            </template>

                            <!-- Add Allowance Row -->
                             @if(!$isDisabled)
                                <tr>
                                    <td class="border-t-2 border-gray-300"></td>
                                    <td class="border-t-2 border-gray-300 text-center font-medium">
                                        <div class="text-blue-500 p-2 cursor-pointer hover:scale-105" @click="addAllowance()">+ Add Allowance</div>
                                    </td>
                                    <td class="border-t-2 border-gray-300"></td>
                                </tr>
                             @endif

                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        
        <!-- Remarks -->
        <div class="input-group">
            <label>Remarks and Other Consideration:</label>
            <textarea class="w-full h-30 resize-none" wire:model="remarks" {{$isDisabled ? 'Disabled' : ''}}></textarea>
        </div>

        <!-- Prepared By -->
        <div class="input-group">
            <label>Prepared By:</label>
            <p>Iverson Guno</p>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-4">
            
            @if($module == 'hr_preparer')
                <div class="flex flex-col">
                    <h1><b>HR Preparer Actions:</b></h1>
                    @if($requestEntry->request_status == 'For HR Prep')
                        <h2>Status (For HR Prep)</h2>
                        <li>Submit for Confirmation</li>
                        <li>Reset</li>

                        <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                            <button type="button" @click="validateBeforeModal('submit')" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit for Confirmation</button>
                            <button type="button" @click="resetForm()" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
                        </div>
                    @endif
                    
                    @if($requestEntry->request_status == 'For Resolution')
                        <h2>Status (For Resolution)</h2>
                        <li>Resubmit for Confirmation</li>
                    @endif
                </div>            
            @endif
            
            @if($module == 'division_head')
                <div class="flex flex-col">
                    <h1><b>Division Head Actions:</b></h1>
                    @if($requestEntry->request_status == 'For Confirmation')
                        <h2>Status (For Confirmation)</h2>
                        <li>Confirm PAN Form</li>
                        <li>Flag for Resolution</li> 

                        <div class="form-buttons  bottom-0 right-0 flex gap-3 justify-end pb-10 md:pb-0 md:mb-0 md:absolute">
                            <button type="button" @click="modalTarget = 'confirmpan'; showModal = true " class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Confirm PAN Form</button>
                            <button type="button" @click="modalTarget = 'disputeHead'; showModal = true " class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Flag for Resolution</button>
                        </div>
                    @endif
                </div>     
            @endif

            @if($module == 'hr_approver')
                <div class="flex flex-col">
                    <h1><b>HR Approver Actions:</b></h1>
                    @if($requestEntry->request_status == 'For HR Approval')
                        <h2>Status (For HR Approval)</h2>
                        <li>Approve Request</li>
                        <li>Reject Request</li>
                    @endif
                </div>
            @endif

            @if($module == 'final_approver')
                <div class="flex flex-col">
                    <h1><b>Final Approver Actions:</b></h1>
                    @if($requestEntry->request_status == 'For Final Approval')
                        <h2>Status (For Final Approval)</h2>
                        <li>Approve Request</li>
                        <li>Reject Request</li>
                    @endif
                </div>            
            @endif

        </div>

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
                        <div class="input-group">
                            <label>Details :</label>
                            <textarea class="w-full h-24 resize-none" wire:model="body" required></textarea>
                        </div>
                    </div>
                </template>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                    <button type="button" @click="showModal = false; $wire[modalConfig[modalTarget]?.action](); resetForm()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                </div>
            </div>

        </di>

    </div>
</section>

<script>
    function panForm() {
        return {
            // --- UI State ---
            showModal: false,
            showAction: false,

            modalTarget: '',
            modalConfig: {
                submit: {
                    header: 'Submit for Confirmation',
                    message: 'This PAN form will be forwarded to the Division Head for confirmation. Are you sure you want to proceed?',
                    action: 'submitPan',
                    needsInput: false
                },

                confirmpan: {
                    header: 'Confirm PAN Form',
                    message: 'This PAN form will be forwarded to the HR Approver for approval. Are you sure you want to proceed?',
                    action: 'confirmPan',
                    needsInput: false
                },

                disputeHead: {
                    header: 'Flag for Resolution',
                    action: 'disputeHead',
                    needsInput: true
                },
            },

            validationAttempted: false,

            // --- Row validation state ---
            rows: {
                section: { valid: false },
                place: { valid: false },
                head: { valid: false },
                position: { valid: false },
                joblevel: { valid: false },
                basic: { valid: false },
            },

            // --- Allowances ---
            allowances: [],
            allOptions: ["Communication Allowance", "Meal Allowance", "Living Allowance", "Transportation Allowance", "Clothing Allowance", "Fuel Allowance", "Management Allowance", "Developmental Assignments", "Professional Allowance", "Interim Allowance", "Training Allowance", "Mancom Allowance"],

            addAllowance() {
                this.allowances.push({ from: "", to: "", value: "" });
                this.syncAllowancesWithLivewire();
            },

            updateAllowance(index, value) {
                this.allowances[index].value = value;
                this.checkRows();
                this.syncAllowancesWithLivewire();
            },

            // Add this method to sync allowances with Livewire
            syncAllowancesWithLivewire() {
                // Clean the data by removing the 'valid' property before sending to Livewire
                const cleanAllowances = this.allowances.map(({valid, ...allowance}) => allowance);
                this.$wire.call('updateAllowances', cleanAllowances);
            },

            getAvailableOptions(index) {
                return this.allOptions.filter((opt) => !this.allowances.map((a) => a.value).includes(opt) || this.allowances[index].value === opt);
            },

            // --- Init ---
            init() {
                // Listen to static input fields
                this.getFields().forEach((field) => {
                    ["input", "change"].forEach((evt) => field.addEventListener(evt, () => this.checkFields()));
                });
                this.checkFields();
            },

            // --- Field Helpers ---
            getFields() {
                return [
                    this.$refs.date_hired, 
                    this.$refs.employment_status, 
                    this.$refs.division, 
                    this.$refs.date_of_effectivity
                ];
            },

            // --- Validation ---
            checkFields() {
                this.showAction = this.getFields().some((f) => f.value?.trim() !== "");
            },

            checkRows() {
                // static rows
                Object.keys(this.rows).forEach((key) => {
                    const from = this.$refs[`${key}_from`]?.value?.trim();
                    const to = this.$refs[`${key}_to`]?.value?.trim();
                    this.rows[key].valid = !!(from && to);
                });

                // allowance rows (directly from x-model)
                this.allowances.forEach((a) => {
                    a.valid = !!(a.from?.trim() && a.to?.trim() && a.value);
                });

                // Sync with Livewire after validation
                this.syncAllowancesWithLivewire();
            },

            validateBeforeModal(action) {
                this.validationAttempted = true;

                // highlight required static fields
                let hasEmpty = false;
                this.getFields().forEach((field) => {
                    if (field.value.trim() === "") {
                        field.classList.add("!border-red-500");
                        hasEmpty = true;
                    } else {
                        field.classList.remove("!border-red-500");
                    }
                });

                if (hasEmpty) return; // Stop here if static fields are empty

                // Now check rows only if static fields are valid
                this.checkRows();

                const rowsInvalid = Object.values(this.rows).some((r) => !r.valid);
                const allowancesInvalid = this.allowances.some((a) => !a.valid);

                if (rowsInvalid || allowancesInvalid) return;

                this.syncAllowancesWithLivewire();

                this.modalTarget = action;
                this.showModal = true;
            },

            // Add method to handle allowance deletion
            removeAllowance(index) {
                this.allowances.splice(index, 1);
                this.syncAllowancesWithLivewire();
            },

            resetForm() {
                // Reset all refs
                this.getFields().forEach(field => field.value = "");

                // Reset rows
                Object.keys(this.rows).forEach(key => {
                    this.rows[key].valid = false;
                    if (this.$refs[`${key}_from`]) this.$refs[`${key}_from`].value = "";
                    if (this.$refs[`${key}_to`]) this.$refs[`${key}_to`].value = "";
                });

                // Reset allowances
                this.allowances = [];

                // Reset validation
                this.validationAttempted = false;

                // Sync to Livewire (optional, if you want Livewire to clear too)
                this.syncAllowancesWithLivewire();
            }
        };
    }
</script>

