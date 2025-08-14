<section class="content-block" id="content-block-2">
    <h1 class="text-[22px]">PAN Preparation Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full">
        <div class="input-fields grid grid-cols-4 gap-4">
            <div class="input-group">
                <label for="">Date Hired:</label>
                <input type="date">
            </div>
            <div class="input-group">
                <label for="">Employement Status:</label>
                <input type="text">
            </div>
            <div class="input-group">
                <label for="">Division:</label>
                <input type="text">
            </div>
            <div class="input-group">
                <label for="">Date of Effectivity:</label>
                <input type="date">
            </div>
        </div>

        <div class="table-group w-full flex flex-col gap-3">
            <label class="text-[18px] mb-2">Action Reference Table:</label>

            <!-- Outer wrapper for rounding and border -->
            <div class="overflow-hidden rounded-md border border-2 border-gray-300">
                <table class="w-full table-fixed border-separate border-spacing-0">
                    <thead class="bg-white">
                            <tr>
                                <th class="w-1/3 border-gray-300 text-center">From</th>
                                <th class="w-1/3 border-gray-300 text-center">Action Reference</th>
                                <th class="w-1/3 border-gray-300 text-center">To</th>
                            </tr>
                    </thead>
                    <tbody id="pan-tbody" 
                        x-data="{
                            allowances: [],
                            allOptions: [
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
                            ],
                            updateAllowance(index, value) {
                            this.allowances[index] = value;
                            },
                            getAvailableOptions(index) {
                            return this.allOptions.filter(opt => 
                                !this.allowances.includes(opt) || this.allowances[index] === opt
                            );
                            }
                        }">

                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                            <td class="border-t-2 border-gray-300 text-center">Section</td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                            <td class="border-t-2 border-gray-300 text-center">Place of Assignment</td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                            <td class="border-t-2 border-gray-300 text-center">Immediate Head</td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                            <td class="border-t-2 border-gray-300 text-center">Position</td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                            <td class="border-t-2 border-gray-300 text-center">Job Level</td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                            <td class="border-t-2 border-gray-300 text-center">Basic</td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                            </td>
                        </tr>
                        <!-- Allowance rows -->
                        <template x-for="(allowance, index) in allowances" :key="index">
                            <tr>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                </td>
                                <td class="border-t-2 relative border-gray-300 text-center font-medium">
                                    <i class="fa-regular fa-trash-can absolute left-[10px] top-[15px] cursor-pointer text-red-600 hover:scale-110"
                                    @click="allowances.splice(index, 1)">
                                    </i>
                                    <select class="w-full border-none focus:ring-0 text-center outline-none p-0"
                                            x-model="allowances[index]"
                                            @change="updateAllowance(index, $event.target.value)">
                                    <option value="">Select Allowance</option>
                                    <template x-for="opt in getAvailableOptions(index)" :key="opt">
                                        <option x-text="opt"></option>
                                    </template>
                                    </select>
                                </td>
                                <td class="border-t-2 border-gray-300">
                                    <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                </td>
                            </tr>
                        </template>
                        <tr>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" disabled/>
                            </td>
                            <td class="border-t-2 border-gray-300 text-center font-medium">
                                <div class="text-blue-500 cursor-pointer hover:scale-105" @click="allowances.push('')">+ Add Allowance</div>
                            </td>
                            <td class="border-t-2 border-gray-300">
                                <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" disabled/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="input-group">
            <label for="">Remarks and Other Consideration:</label>
            <textarea name="" id="" class="w-full h-30 resize-none"></textarea>
        </div>

        <div class="input-group">
            <label for="">Prepared By:</label>
            <p>Iverson Guno</p>
        </div>

        <!-- Alpine instance -->
        <div x-data="{ showModal: false, modalMessage: '' }">

            <!-- Buttons -->
            <div class="form-buttons absolute bottom-0 right-0 flex gap-3 justify-end">
                <button @click="modalMessage = 'Are you sure you want to submit?'; showModal = true" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit for Approval</button>
                <button @click="modalMessage = 'Save this as a draft?'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Save as Draft</button>
                <button @click="modalMessage = 'Reset the form?'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
            </div>

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
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 z-10">
                    <h2 class="text-lg font-semibold mb-4">Confirmation</h2>
                    <p class="mb-6" x-text="modalMessage"></p>
                    <div class="flex justify-end gap-3">
                        <button @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        <button @click="showModal = false" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>