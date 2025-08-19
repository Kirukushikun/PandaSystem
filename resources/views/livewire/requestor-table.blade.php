<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">My Requests</h1>
        <x-search-sort-filter/>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Request No</th>
                    <th>Employee Name</th>
                    <th>Type of Action</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th>Last Update</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($myRequests as $request)
                    <tr>
                        <td>{{$request->request_no}}</td>
                        <td>{{$request->employee_name}}</td>
                        <td>{{$request->type_of_action ?? '--'}}</td>
                        <td>{{$request->submitted_at ? $request->submitted_at->format('m/d/Y') : '--'}}</td>
                        <td>
                                <x-statustag :status-text="$request->request_status" status-location="Table"/>
                        </td>
                        <!-- <td>{{$request->updated_at->format('m/d/Y - h:i A')}}</td> -->
                         <td>{{$request->updated_at->format('m/d/Y')}}</td>
                        <td class="table-actions">
                            <button class="bg-blue-600 text-white" onclick="window.location.href='/requestor-view?requestID={{$request->id}}'">View</button>
                            <!-- <i class="fa-solid fa-box-archive"></i> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @php
        $currentPage = $myRequests->currentPage();
        $lastPage = $myRequests->lastPage();

        // Calculate range of page numbers
        $start = max(1, $currentPage - 2);
        $end = min($lastPage, $start + 4);

        // Adjust start if we're at the end
        if ($end - $start < 4) {
            $start = max(1, $end - 4);
        }
    @endphp

    <div class="pagination-container flex items-center justify-end gap-3">
        <div class="text-sm text-gray-600">
            Showing {{ $myRequests->firstItem() }} to {{ $myRequests->lastItem() }} of {{ $myRequests->total() }} results
        </div>
        
        {{-- Left Arrow --}}
        @if ($currentPage > 1)
            <button 
                wire:click="goToPage({{ $currentPage - 1 }})"
                class="px-4 py-2 rounded-md hover:scale-110 cursor-pointer bg-blue-100 text-blue-600">
                <i class="fa-solid fa-caret-left"></i>
            </button>
        @endif

        {{-- Page Numbers --}}
        @for ($i = $start; $i <= $end; $i++)
            <button 
                wire:click="goToPage({{ $i }})"
                class="{{ $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-600' }} 
                    px-4 py-2 rounded-md hover:scale-110 cursor-pointer">
                {{ $i }}
            </button>
        @endfor

        {{-- Right Arrow --}}
        @if ($currentPage < $lastPage)
            <button 
                wire:click="goToPage({{ $currentPage + 1 }})"
                class="px-4 py-2 rounded-md hover:scale-110 cursor-pointer bg-blue-100 text-blue-600">
                <i class="fa-solid fa-caret-right"></i>
            </button>
        @endif
    </div> 
</div>