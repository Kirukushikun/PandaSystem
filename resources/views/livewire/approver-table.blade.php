<section class="content-block" id="content-block-1">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Approval Requests</h1>
        <x-search-sort-filter/>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Request No</th>
                    <th>Employee Name</th>
                    <th>Type of Action</th>
                    <th>Requested By</th>
                    <th>Prepared By</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
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
                        <td>Chris Bacon</td>
                        <td>John Doe</td>
                        <td>
                            <div class="status-tag {{ $statusColor }}">{{ $statusText }}</div>
                        </td>
                        <td>09/12/2025</td>
                        <td>09/12/2025</td>
                        <td class="table-actions">
                            <button class="bg-blue-600 text-white" onclick="window.location.href='/approver-view'">View</button>
                            <i class="fa-solid fa-box-archive"></i>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <x-pagination/>
</section>