<div class="flex flex-col h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">PAN Requests</h1>
        <x-search-sort-filter role="hrpreparer" farmFilter="true"/>
    </div>
    <div class="ml-1" x-data="{ filter: localStorage.getItem('hrpreparer') || 'all' }" 
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
                    @foreach($panRequests as $request)
                        <tr>
                            <td>{{$request->request_no}}</td>
                            <td>{{$request->employee_name}}</td>
                            <td>{{$request->type_of_action}}</td>
                            <td>{{$request->requested_by}}</td>
                            <td>{{$request->submitted_at->format('m/d/Y')}}</td>
                            <td>
                                <x-statustag :status-text="$request->request_status" status-location="Table"/>
                            </td>
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
                                    <i class="fa-solid fa-print text-gray-400"></i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>
        </div>
        <x-pagination :paginator="$panRequests" />
    @endif
</div>