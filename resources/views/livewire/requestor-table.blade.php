<section class="content-block" id="content-block-1">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">My Requests</h1>
        <div class="search-bar flex items-center flex-initial w-sm px-5 py-2 ml-auto border-solid border-2 border-gray-300 bg-gray-100 rounded-lg">
            <input type="text" class="search-input bg-gray-100 p-0 w-full border-none outline-none focus:ring-0" placeholder="Search...">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        </div>
        <select name="sortby" id="sortby" class="sortby flex-none border-solid border-2 border-gray-300 bg-gray-200 rounded-md">
            <option value="">Sort By</option>
        </select>
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
                        'Returned' => 'bg-yellow-100 text-yellow-500',
                        'For Approval' => 'bg-orange-100 text-orange-500',
                        'Rejected' => 'bg-red-100 text-red-500',
                        'Approved' => 'bg-green-100 text-green-500',
                    ];
                @endphp
                @for($i = 0; $i < 9; $i++)
                    @php
                        $statusText = array_rand($statuses);
                        $statusColor = $statuses[$statusText];
                    @endphp
                    <tr>
                        <td>PAN-2025-001</td>
                        <td>Juan Dela Cruz</td>
                        <td>Promotion</td>
                        <td>09/12/2025</td>
                        <td>
                                <div class="status-tag {{ $statusColor }}">{{ $statusText }}</div>
                        </td>
                        <td>09/12/2025</td>
                        <td class="table-actions">
                                <button class="bg-blue-600 text-white" onclick="window.location.href='/requestor-view'">View</button>
                                <i class="fa-solid fa-box-archive"></i>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div class="pagination-container flex justify-end gap-3">
        <a class="px-4 py-2 bg-blue-600 text-white rounded-md hover:scale-110" href="">1</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">2</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">3</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">4</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">5</a>
    </div>
</section>