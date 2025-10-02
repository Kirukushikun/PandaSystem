<div class="flex flex-col gap-5 h-full">
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
                            <button class="bg-blue-600 text-white" onclick="window.location.href='/{{$module == 'hrpreparer' ? 'hrpreparer' : 'hrapprover'}}/employeerecord-view?requestID={{ encrypt($record->company_id) }}'">View Records</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$panRecords" />

</div>