<section class="content-block" id="content-block-1">
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
                @php
                    $statuses = [
                        'Draft' => 'bg-gray-100 text-gray-500',
                        'For Prep' => 'bg-blue-100 text-blue-500',
                        'Returned' => 'bg-yellow-100 text-yellow-600',
                        'For Approval' => 'bg-orange-100 text-orange-500',
                        'Rejected' => 'bg-red-100 text-red-500',
                        'Approved' => 'bg-green-100 text-green-600',
                    ];
                @endphp
                @foreach($myRequests as $request)
                    <tr>
                        <td>{{$request->request_no}}</td>
                        <td>{{$request->employee_name}}</td>
                        <td>{{$request->type_of_action ?? '--'}}</td>
                        <td>{{$request->submitted_at ?? '--'}}</td>
                        <td>
                                <div class="status-tag {{ $statuses[$request->request_status] }}">{{$request->request_status}}</div>
                        </td>
                        <!-- <td>{{$request->updated_at->format('m/d/Y - h:i A')}}</td> -->
                         <td>{{$request->updated_at->format('m/d/Y')}}</td>
                        <td class="table-actions">
                                <button class="bg-blue-600 text-white" onclick="window.location.href='/requestor-view?requestID={{$request->id}}'">View</button>
                                <i class="fa-solid fa-box-archive"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination/>
</section>