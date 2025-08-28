


<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">User Access</h1>
        <x-search-sort-filter/>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>RQ Module</th>
                    <th>DH Module</th>
                    <th>HRP Module</th>
                    <th>HRA Module</th>
                    <th>FA Module</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user['first_name'] ?? '-' }} {{ $user['last_name'] ?? '-' }}</td>
                        <td class="table-actions">
                            <button class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white" onclick="window.location.href='/requestor-view'">Grant</button>
                        </td>
                        <td class="table-actions">
                            <button class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white" onclick="window.location.href='/requestor-view'">Grant</button>
                        </td>
                        <td class="table-actions">
                            <button class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white" onclick="window.location.href='/requestor-view'">Revoke</button>
                        </td>
                        <td class="table-actions">
                            <button class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white" onclick="window.location.href='/requestor-view'">Revoke</button>
                        </td>
                        <td class="table-actions">
                            <button class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white" onclick="window.location.href='/requestor-view'">Grant</button>
                        </td>
                    </tr>
                @endforeach
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