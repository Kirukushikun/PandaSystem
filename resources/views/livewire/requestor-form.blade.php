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
        <textarea name="" id="" class="w-full h-full resize-none"></textarea>
    </div>

    <div class="input-group">
        <label for="">Supporting Files:</label>
        <input class="block object-fit text-md text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" id="multiple_files" type="file" multiple>
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