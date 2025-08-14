
<section class="content-block h-full" id="content-block-2">
    <h1 class="text-[22px]">Request Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full">
        <div class="input-fields grid grid-cols-4 gap-4">
            <div class="input-group">
                <label for="">Employee Name:</label>
                <input type="text">
            </div>
            <div class="input-group">
                <label for="">Employee ID:</label>
                <input type="text">
            </div>
            <div class="input-group">
                <label for="">Department:</label>
                <select name="" id="">
                    <option value="">Select department</option>
                    <option value="">FEEDMILL</option>
                    <option value="">FOC</option>
                    <option value="">GENERAL SERVICES</option>
                    <option value="">HR</option>
                    <option value="">IT & SECURITY</option>
                    <option value="">POULTRY</option>
                    <option value="">PURCHASING</option>
                    <option value="">SALES & MARKETING</option>
                    <option value="">SWINE</option>
                </select>
            </div>
            <div class="input-group">
                <label for="">Type of Action:</label>
                <select name="" id="">
                    <option value="">Select type</option>
                    <option value="">Regularization</option>
                    <option value="">Salary Alignment</option>
                    <option value="">Wage Order</option>
                    <option value="">Lateral Transfer</option>
                    <option value="">Developmental Assignment</option>
                    <option value="">Interim Allowance</option>
                    <option value="">Promotion</option>
                    <option value="">Training Status</option>
                    <option value="">Confirmation of Appointment</option>
                </select>
            </div>
        </div>

        <div class="input-group h-full">
            <label for="">Justification:</label>
            <textarea name="" id="" class="w-full h-full resize-none"></textarea>
        </div>

        <div class="file-group flex flex-col gap-2">
            <label for="" class="text-[18px]">Supporting Files:</label>
            <input class="block w-full text-sm text-gray-500 border border-1 border-gray-600 rounded-md cursor-pointer bg-gray-50 focus:outline-none" id="file_input" type="file">
        </div>
        <div class="input-group">
            <label for="">Requested By:</label>
            <p>Iverson Guno</p>
        </div>

        <!-- Alpine instance -->
        <div x-data="{ showModal: false, modalMessage: '' }">

            <!-- Buttons -->
            <div class="form-buttons absolute bottom-0 right-0 flex gap-3 justify-end">
                <button @click="modalMessage = 'Are you sure you want to submit?'; showModal = true" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Submit</button>
                <button @click="modalMessage = 'Save this as a draft?'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Save as Draft</button>
                <button @click="modalMessage = 'Reset the form?'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
            </div>

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
