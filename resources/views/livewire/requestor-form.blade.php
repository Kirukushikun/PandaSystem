
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

        <div class="form-buttons absolute bottom-0 right-0 flex gap-3 justify-end">
            <button class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800">Submit</button>
            <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Save as Draft</button>
            <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
        </div>
    </div>    
</section>
