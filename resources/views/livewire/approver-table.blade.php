<div class="flex flex-col gap-5 h-full" 
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
                needsInput: false
            },
        },
    }"
>
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Approval Requests</h1>
        <x-search-sort-filter role="finalapprover"/>

        <button type="button" x-show="!showActions" @click="showActions = true" class="border-solid border-3 border-gray-300 text-blue-600 px-4 py-2 rounded-md cursor-pointer font-bold hover:bg-blue-600 hover:border-blue-600 hover:text-white">Select</button>
        <!-- Action buttons -->
        <div x-show="showActions" class="flex gap-2">
            <button type="button" @click="modalTarget = 'approve'; showModal = true" class="border-solid border-3 border-green-600 bg-green-600 text-white px-4 py-2 rounded-md cursor-pointer font-bold hover:bg-green-700 hover:border-green-700">Approve</button>
            <button type="button" @click="modalTarget = 'reject'; showModal = true" class="border-solid border-3 border-red-600 bg-red-600 text-white px-4 py-2 rounded-md cursor-pointer font-bold hover:bg-red-700 hover:border-red-700">Reject</button>
            <button type="button" @click="showActions = false" class="border-solid border-3 border-gray-300 text-blue-600 px-4 py-2 rounded-md cursor-pointer font-bold hover:bg-blue-600 hover:border-blue-600 hover:text-white">Cancel</button>
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
                                        wire:model.defer="selectedRequests"
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

            <div class="flex justify-end gap-3">
                <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                <button type="button" @click="showModal = false; $wire[modalConfig[modalTarget]?.action]()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
            </div>
        </div>
    </div>
</div>