<div class="flex flex-col h-full" 
    x-data="{
        showModal: false,
        targetEntry: '',
    }"
>
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">PAN Requests</h1>
        <x-search-sort-filter role="hrpreparer" farmFilter="true"/>
    </div>
    <div class="ml-1" x-data="{ filter: localStorage.getItem('hrpreparer') || 'in_progress' }" 
        x-init="$wire.set('filterStatus', filter)">
        <div class="flex gap-4 mb-4">
            <label>
                <input type="radio" name="status_filter" value="all" 
                    x-model="filter" 
                    @change="localStorage.setItem('hrpreparer', filter); $wire.set('filterStatus', filter)">
                All
            </label>

            <label>
                <input type="radio" name="status_filter" value="in_progress" 
                    x-model="filter" 
                    @change="localStorage.setItem('hrpreparer', filter); $wire.set('filterStatus', filter)">
                In Progress
            </label>

            <label>
                <input type="radio" name="status_filter" value="completed" 
                    x-model="filter" 
                    @change="localStorage.setItem('hrpreparer', filter); $wire.set('filterStatus', filter)">
                Completed
            </label>
        </div>
    </div>
    @if($panRequests->isEmpty())
        <div class="empty-promt w-full h-full flex flex-col items-center justify-center rounded text-gray-400 select-none">
            <i class="fa-solid fa-box-open text-xl mb-1"></i>
            <p class="text-lg">There is no request entry yet</p>
        </div>
    @else 
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Request No</th>
                        <th>Status</th>
                        <th>Employee Name</th>
                        <th>Type of Action</th>
                        <th>Requested By</th>
                        <th>Date Submitted</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($panRequests as $request)
                        <tr>
                            <td> 
                                @if(Auth::user()->role == 'hrhead')
                                    @if($request->confidentiality)
                                        <i class="fa-solid fa-circle !text-xs text-{{$request->confidentiality == 'manila' ? 'purple' : 'blue'}}-400 cursor-pointer" title="{{$request->confidentiality}}"></i> 
                                    @else
                                        <i class="fa-solid fa-circle !text-xs text-gray-300 cursor-pointer" title="Not set"></i> 
                                    @endif
                                @endif 
                                {{$request->request_no}}
                            </td>
                            <td>
                                <x-statustag :status-text="$request->request_status" status-location="Table"/>
                            </td>
                            <td>{{$request->employee_name}}</td>
                            <td>{{$request->type_of_action}}</td>
                            <td>{{$request->requested_by ?? '--'}}</td>
                            <td>{{$request->submitted_at->format('m/d/Y')}}</td>
                            <td>{{$request->updated_at->format('m/d/Y')}}</td>
                            <td class="table-actions">
                                @if($request->confidentiality != 'manila' && Auth::user()->role != 'hrhead')
                                    <button class="bg-blue-600 text-white" onclick="window.location.href='/hrpreparer-view?requestID={{ encrypt($request->id) }}'">View</button>
                                    @if(in_array($request->request_status, ['Approved', 'Served', 'Filed']))
                                        <i class="fa-solid fa-print" onclick="window.location.href='/print-view?requestID={{ encrypt($request->id) }}'"></i>
                                    @endif
                                @elseif(Auth::user()->role == 'hrhead')
                                    <button class="bg-blue-600 text-white" onclick="window.location.href='/hrpreparer-view?requestID={{ encrypt($request->id) }}'">View</button>
                                    @if(in_array($request->request_status, ['Approved', 'Served', 'Filed']))
                                        <i class="fa-solid fa-print" onclick="window.location.href='/print-view?requestID={{ encrypt($request->id) }}'"></i>
                                    @endif
                                @else 
                                    <button class="bg-gray-400 text-white">View</button>
                                    @if(in_array($request->request_status, ['Approved', 'Served', 'Filed']))
                                        <i class="fa-solid fa-print text-gray-400"></i>
                                    @endif
                                @endif
                                
                                @if(!in_array($request->request_status, ['Served', 'Filed']))
                                    <i class="fa-solid fa-trash text-red-600" @click="showModal = true; targetEntry = {{$request->id}}"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
        <x-pagination :paginator="$panRequests" />

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
            <div class="bg-white p-6 rounded-lg shadow-lg w-lg z-10">
                <h2 class="text-xl font-semibold mb-4">Delete PAN Initiation</h2>
                <p class="mb-6">Warning: You are about to permanently void this Personnel Action Notice (PAN). This action is irreversible and will be logged in the system's audit trail.</p>

                <div class="input-group mb-3">
                    <label for="header"><span class="text-red-600 font-bold">*</span> Mandatory Reason for Deletion :</label>
                    <!-- <input type="text" name="header" wire:model="header" required> -->
                    <select name="header" wire:model="header" required>
                        <option value="">Select a documented reason</option>
                        <option value="Duplicate Entry">Duplicate Entry</option>
                        <option value="Wrong Employee/Action Type">Incorrect Employee or Action Type Selected</option>
                        <option value="Data Entry Mistakes">Significant Data Entry Error (Uncorrectable)</option>
                        <option value="Action Rescinded">Underlying Action Rescinded (e.g., candidate withdrawal)</option>
                        <option value="Policy Violation">Identified Policy or Compliance Violation</option>
                        <option value="Other">Other (Note required below)</option>
                    </select>
                </div>

                <div class="input-group mb-3" x-show="$wire.header === 'Other'">
                    <label><span class="text-red-600 font-bold">*</span> Custom Reason :</label>
                    <input type="text" class="w-full" placeholder="Type your reason" wire:model="customHeader">
                </div>

                <div class="input-group mb-5">
                    <label><span class="text-red-600 font-bold">*</span> Details :</label>
                    <textarea class="w-full h-24 resize-none" wire:model="body" required></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                    <button type="button" @click="showModal = false; $wire.deleteEntry(targetEntry)" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                </div>
            </div>
        </div>
    @endif
</div>