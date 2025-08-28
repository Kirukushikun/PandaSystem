<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Head Approval Requests</h1>
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
                @foreach($requests as $request)
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
                            <button class="bg-blue-600 text-white" onclick="window.location.href='/divisionhead-view?requestID={{ encrypt($request->id) }}'">View</button>
                            <!-- <i class="fa-solid fa-box-archive"></i> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$requests" />

</div>