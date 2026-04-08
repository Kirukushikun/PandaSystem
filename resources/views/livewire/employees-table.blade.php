<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Employees table</h1>

        <div class="search-bar flex items-center flex-initial w-sm px-5 py-2 ml-auto border-solid border-2 border-gray-300 bg-gray-100 rounded-lg">
            <input
                type="text"
                class="search-input bg-gray-100 p-0 w-full border-none outline-none focus:ring-0"
                placeholder="Search..."
                wire:model.live="search"
            >
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        </div>

        <form action="/import" method="POST" enctype="multipart/form-data">
            @csrf
            <label class="bg-blue-600 text-white hover:bg-blue-700 rounded-md cursor-pointer px-3 py-2">
                <i class="fa-solid fa-file-import"></i>
                Import
                <input type="file" name="file" accept=".xlsx,.xls,.csv" class="hidden" onchange="this.form.submit()">
            </label>
        </form>

        <a href="/export" class="bg-blue-600 text-white hover:bg-blue-700 rounded-md cursor-pointer px-3 py-2">
            <i class="fa-solid fa-file-export"></i>
            Export
        </a>

        <i wire:click="openCreateModal" class="fa-solid fa-user-plus cursor-pointer hover:scale-120"></i>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Company ID</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Farm</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr wire:key="employee-{{ $employee->id }}">
                        <td>{{ $employee->company_id }}</td>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->farm }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>No Ongoing Pan</td>
                        <td>
                            <i wire:click="openEditModal({{ $employee->id }})" class="fa-solid fa-pen-to-square cursor-pointer hover:scale-120"></i>
                            <i wire:click="openDeleteModal({{ $employee->id }})" class="fa-solid fa-trash cursor-pointer hover:scale-120"></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$employees" />

    @if($showModal)
        <div class="fixed inset-0 bg-black/50 z-40" wire:click="closeModal"></div>

        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-md z-10">
                @if($modalType === 'create' || $modalType === 'edit')
                    <h2 class="text-2xl font-semibold mb-4">
                        {{ $modalType === 'create' ? 'Add Employee' : 'Edit Employee Details' }}
                    </h2>

                    <div class="input-group mb-2">
                        <label>Company ID:</label>
                        <input type="text" wire:model.defer="company_id">
                    </div>

                    <div class="input-group mb-2">
                        <label>Employee Name:</label>
                        <input type="text" wire:model.defer="employee_name">
                    </div>

                    <div class="input-group mb-4">
                        <label>Position:</label>
                        <input type="text" wire:model.defer="employee_position">
                    </div>

                    <div class="input-group mb-2">
                        <label>Farm:</label>
                        <select wire:model.defer="employee_farm">
                            <option value="">Select Farm</option>
                            <option value="BFC">BFC</option>
                            <option value="BDL">BDL</option>
                            <option value="PFC">PFC</option>
                            <option value="RH">RH</option>
                        </select>
                    </div>

                    <div class="input-group mb-4">
                        <label>Department:</label>
                        <input type="text" wire:model.defer="employee_department">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        @if($modalType === 'create')
                            <button type="button" wire:click="createEmployee" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                        @else
                            <button type="button" wire:click="updateEmployee" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                        @endif
                    </div>
                @endif

                @if($modalType === 'delete')
                    <h2 class="text-xl font-semibold mb-4">Delete Employee</h2>
                    <p class="mb-6">Are you sure you want to delete this employee? Action cannot be undone.</p>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        <button type="button" wire:click="deleteEmployee" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
