<section class="content-block relative" id="content-block-1">
    <div class="form-buttons absolute top-[35px] right-[40px] flex gap-3 justify-end">
        <div class="request-status bg-blue-100 text-blue-500">For Prep</div>
        <!-- <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button> -->
    </div>

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
                <input type="text">
            </div>
            <div class="input-group">
                <label for="">Type of Action:</label>
                <input type="text">
            </div>
        </div>

        <div class="input-group h-full">
            <label for="">Justification:</label>
            <textarea name="" id="" class="w-full h-50 resize-none"></textarea>
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

        <div class="form-buttons absolute bottom-0 right-0 flex gap-3 justify-end">
            <button class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800">Return to Requestor</button>
            <!-- <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button> -->
        </div>
    </div>
</section>

