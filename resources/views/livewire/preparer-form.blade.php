<section class="content-block relative" id="content-block-1">
    <div class="form-buttons absolute top-[35px] right-[40px] flex gap-3 justify-end">
        <div class="request-status bg-blue-100 text-blue-500">For Prep</div>
        <!-- <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button> -->
    </div>

    <h1 class="text-[22px]">Request Form</h1>
    <div class="form-container relative flex flex-col gap-5 h-full">

        <div class="input-fields grid grid-cols md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="input-group">
                <label for="">Employee Name:</label>
                <input type="text" readonly>
            </div>
            <div class="input-group">
                <label for="">Employee ID:</label>
                <input type="text" readonly>
            </div>
            <div class="input-group">
                <label for="">Department:</label>
                <input type="text" readonly>
            </div>
            <div class="input-group">
                <label for="">Type of Action:</label>
                <input type="text" readonly>
            </div>
        </div>

        <div class="input-group h-full">
            <label for="">Justification:</label>
            <textarea name="" id="" class="w-full h-50 resize-none" readonly></textarea>
        </div>

        <div class="file-group flex flex-col gap-2">
            <label for="" class="text-[18px]">Supporting Files:</label>
            <div class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm">
                <!-- Button -->
                <button class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500">
                    View File
                </button>
                <!-- File Name -->
                <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                    sample_document.pdf
                </div>
            </div>
        </div>
        
        <div class="input-group">
            <label for="">Requested By:</label>
            <p>Iverson Guno</p>
        </div>

        <!-- Alpine instance -->
        <div x-data="{ showModal: false, modalMessage: '' }">

            <!-- Buttons -->
            <div class="form-buttons bottom-0 right-0 flex gap-3 justify-end md:mb-0 md:absolute">
                <button @click="modalMessage = 'Are you sure you want to submit?'; showModal = true" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Return to Requestor</button>
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

