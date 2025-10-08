<div class="flex flex-col h-full" 
    x-data="{
        showActions: false,
        showModal: false,
        modalTarget: '',
        modalConfig: {
            approve: {
                header: 'Approve Selected Requests',
                message: 'Are you sure you want to approve these selected request? This action cannot be undone.',
                action: 'approveRequests',
                needsInput: false
            },
            reject: {
                header: 'Reject Selected Requests',
                message: 'Are you sure you want to reject these selected request? This action cannot be undone.',
                action: 'rejectRequests',
                needsInput: true
            },
        },
    }"
>
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Approval Requests</h1>
        <x-search-sort-filter role="finalapprover"/>
        @if(!$approvalRequests->isEmpty())
            <button type="button" x-show="!showActions" @click="showActions = true" class="border-solid border-3 border-gray-300 text-blue-600 px-4 py-2 rounded-md cursor-pointer font-bold hover:bg-blue-600 hover:border-blue-600 hover:text-white">Select</button>
        @endif
        <!-- Action buttons -->
        <div x-show="showActions" class="flex gap-2">
            <button type="button" @click="modalTarget = 'approve'; showModal = true" @disabled($selectedRequests == []) class="border-solid border-3 text-white px-4 py-2 rounded-md cursor-pointer font-bold {{ $selectedRequests == [] ? 'border-gray-400 bg-gray-400' : 'border-green-600 bg-green-600 hover:bg-green-700 hover:border-green-700'}}">Approve</button>
            <button type="button" @click="modalTarget = 'reject'; showModal = true" @disabled($selectedRequests == []) class="border-solid border-3 text-white px-4 py-2 rounded-md cursor-pointer font-bold {{ $selectedRequests == [] ? 'border-gray-400 bg-gray-400' : 'border-red-600 bg-red-600 hover:bg-red-700 hover:border-red-700'}}">Reject</button>
            <button type="button" @click="showActions = false" class="border-solid border-3 border-gray-300 text-blue-600 px-4 py-2 rounded-md cursor-pointer font-bold hover:bg-blue-600 hover:border-blue-600 hover:text-white">Cancel</button>
        </div>
    </div>
    <div class="ml-1" x-data="{ filter: localStorage.getItem('approver') || 'all' }" 
        x-init="$wire.set('filterStatus', filter)">
        <div class="flex gap-4 mb-4">
            <label>
                <input type="radio" name="status_filter" value="all" 
                    x-model="filter" 
                    @change="localStorage.setItem('approver', filter); $wire.set('filterStatus', filter)">
                All
            </label>

            <label>
                <input type="radio" name="status_filter" value="in_progress" 
                    x-model="filter" 
                    @change="localStorage.setItem('approver', filter); $wire.set('filterStatus', filter)">
                In Progress
            </label>

            <label>
                <input type="radio" name="status_filter" value="completed" 
                    x-model="filter" 
                    @change="localStorage.setItem('approver', filter); $wire.set('filterStatus', filter)">
                Completed
            </label>
        </div>
    </div>
    @if($approvalRequests->isEmpty())
        <div class="empty-promt w-full h-full flex flex-col items-center justify-center rounded text-gray-400 select-none">
            <i class="fa-solid fa-box-open text-xl mb-1"></i>
            <p class="text-lg">There is no pending approvals yet</p>
        </div>
    @else 
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Request No</th>
                        <th>Employee Name</th>
                        <th>Type of Action</th>
                        <th>Requested By</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvalRequests as $request)
                        <tr>
                            <td> 
                                @if($request->request_status === "For Final Approval")
                                    <input 
                                        x-show="showActions" 
                                        type="checkbox" 
                                        value="{{ $request->id }}" 
                                        wire:model.live="selectedRequests"
                                        class="mr-2 border-2 cursor-pointer"
                                    />
                                @else
                                    <span x-show="showActions" class="mr-2 inline-block w-4"></span>
                                @endif
                                {{$request->request_no}}
                            </td>
                            <td>{{$request->employee_name}}</td>
                            <td>{{$request->type_of_action}}</td>
                            <td>{{$request->requested_by}}</td>
                            <td>{{$request->submitted_at->format('m/d/Y')}}</td>
                            <td>
                                <x-statustag :status-text="$request->request_status" status-location="Table"/>
                            </td>
                            <td>{{$request->updated_at->format('m/d/Y')}}</td>
                            <td class="table-actions">
                                <button class="bg-blue-600 text-white" onclick="window.location.href='/approver-view?requestID={{ encrypt($request->id) }}'">View</button>
                                <!-- <i class="fa-solid fa-box-archive"></i> -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>

        
        <x-pagination :paginator="$approvalRequests" />
    @endif

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
            <h2 class="text-xl font-semibold mb-4" x-text="modalConfig[modalTarget]?.header"></h2>
            <p class="mb-6" x-show="!modalConfig[modalTarget]?.needsInput" x-text="modalConfig[modalTarget]?.message"></p>

            <!-- For input type -->
            <template x-if="modalConfig[modalTarget]?.needsInput">
                <div class="flex flex-col gap-3 mb-5">
                    <div class="input-group">
                        <label for="header">Reject Reason :</label>
                        <select name="header" wire:model="header" required>
                            <option value="">Select reason</option>
                            <option value="Incomplete Employee Details">Incomplete Employee Details</option>
                            <option value="Missing Supporting Documents">Missing Supporting Documents</option>
                            <option value="Incorrect Type of Action">Incorrect Type of Action</option>
                            <option value="Unclear or Insufficient Justification">Unclear or Insufficient Justification</option>
                            <option value="Other">Other (Please Specify)</option>
                        </select>
                    </div>

                    <!-- Show this input if "Other" is selected -->
                    <div class="input-group" x-show="$wire.header === 'Other'">
                        <label>Custom Reason :</label>
                        <input type="text" class="w-full" placeholder="Type your reason" wire:model="customHeader">
                    </div>

                    <div class="input-group">
                        <label>Details :</label>
                        <textarea class="w-full h-24 resize-none" placeholder="(Optional)" wire:model="body"></textarea>
                    </div>
                </div>
            </template>

            <div class="flex justify-end gap-3">
                <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                <button type="button" @click="showModal = false; $wire[modalConfig[modalTarget]?.action]()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
            </div>
        </div>
    </div>
</div>