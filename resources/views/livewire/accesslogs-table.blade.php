


<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Access Logs</h1>
        <x-search-sort-filter/>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statuses = [
                        'success' => 'bg-green-100 text-green-500',
                        'failure' => 'bg-red-100 text-red-500',
                    ];
                @endphp
                @for($i = 0; $i < 9; $i++)
                    @php
                        $statusText = array_rand($statuses);
                        $statusColor = $statuses[$statusText];
                    @endphp
                    <tr>
                        <td>61</td>
                        <td>Iverson Guno</td>
                        <td>
                                    <div class="status-tag {{ $statusColor }}">{{ $statusText }}</div>
                        </td>
                        <td>09/12/2025</td>
                        <td>17:20</td>
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

</div>