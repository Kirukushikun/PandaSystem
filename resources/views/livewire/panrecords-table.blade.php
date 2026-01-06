<div class="flex flex-col gap-5 h-full" x-data="{
    showModal: false,
    targetUser: '',
}">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Employee PAN Records</h1>
        <div class="search-bar flex items-center flex-initial w-sm px-5 py-2 ml-auto border-solid border-2 border-gray-300 bg-gray-100 rounded-lg">
            <input type="text" name="search-input" id="search-input" class="search-input bg-gray-100 p-0 w-full border-none outline-none focus:ring-0" placeholder="Search..." wire:model.live="search">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Position</th>
                    <th>Farm</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($panRecords as $record)
                    <tr>
                        <td>{{$record->company_id}}</td>
                        <td>{{$record->full_name}}</td>
                        <td>{{$record->position}}</td>
                        <td>{{$record->farm}}</td>
                        <td>{{$record->department}}</td>
                        <td class="table-actions">
                            @if($module == 'hrpreparer')
                                <button class="bg-blue-600 text-white" onclick="window.location.href='/hrpreparer/employeerecord-view?requestID={{ encrypt($record->company_id) }}'">View Records</button>
                            @elseif($module == 'hrapprover')
                                <button class="bg-blue-600 text-white" onclick="window.location.href='/hrapprover/employeerecord-view?requestID={{ encrypt($record->company_id) }}'">View Records</button>
                            @else
                                <button class="bg-blue-600 text-white" onclick="window.location.href='/approver/employeerecord-view?requestID={{ encrypt($record->company_id) }}'">View Records</button>
                            @endif
                            @if($record->has_ongoing > 0)
                                <i class="fa-solid fa-file-circle-plus text-gray-400" title="Has Ongoing PAN"></i>
                            @else
                                <i class="fa-solid fa-file-circle-plus" @click="showModal = true; targetUser = {{$record->company_id}}"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Overlay (instant) -->
    <div x-show="showModal" class="fixed inset-0 bg-black/50 z-40"></div>

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
            <h2 class="text-xl font-semibold mb-4">Update Employee’s PAN</h2>
            <p class="mb-6">Are you sure you want to update this employee’s PAN? This action will initiate a new PAN entry</p>

            <div class="flex flex-col gap-3 mb-5 mt-[-10px]">
                <div class="input-group">
                    <label><span class="text-red-600 font-bold">*</span> Type of action:</label>
                    <select name="type_of_action" wire:model="type_of_action" required>
                        <option value="">Select type of action</option>
                        <option value="Regularization">Regularization</option>
                        <option value="Wage Order">Wage Order</option>
                        <option value="Lateral Transfer">Lateral Transfer</option>
                        <option value="Developmental Assignment">Developmental Assignment</option>
                        <option value="Interim Allowance">Interim Allowance</option>
                        <option value="Discontinuance of Interim Allowance">Discontinuance of Allowance</option>
                        <option value="Confirmation of Development Assignment">Confirmation of Dev. Assignment</option>
                        <option value="Other Allowances">Other Allowances</option>
                    </select>
                </div>


                <div class="file-group flex flex-col gap-2">
                    <label for="supporting_file" class="text-[18px] relative">Supporting File: 
                        @error('supporting_file')
                            <span class="absolute bg-white text-red-600 right-[10px] bottom-[-20px] text-xs p-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </label>
                    <input name="supporting_file" id="supporting_file" class="block w-full text-sm text-gray-500 border border-1 {{ $errors->has('supporting_file') ? 'border-red-600' : 'border-gray-600' }} rounded-md cursor-pointer bg-gray-50 focus:outline-none" type="file" accept="application/pdf" x-ref="supporting_file" wire:model="supporting_file">
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                <button type="button" @click="showModal = false; $wire.updatePan(targetUser)" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
            </div>
        </div>
    </div>

    <x-pagination :paginator="$panRecords" />

</div>