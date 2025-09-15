<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Employee PAN Records</h1>
        <x-search-sort-filter role="requestor" />
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Position</th>
                    <th>Farm</th>
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
                        <td class="table-actions">
                            <button class="bg-blue-600 text-white" onclick="window.location.href='/hrpreparer-view?requestID={{ encrypt($record->id) }}'">View</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>