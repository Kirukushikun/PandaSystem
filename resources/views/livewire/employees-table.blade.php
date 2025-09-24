<div class="flex flex-col gap-5 h-full"
    x-data="{
        modalOpen: false,
        modalType: '',
        form: { id: null, company_id: '', name: '', farm: '', position: '' },

        openModal(type, data = {}) {
            this.modalType = type;
            this.modalOpen = true;

            if (type === 'edit') {
                this.form = { ...data }; // prefill with user row values
            }
            if (type === 'delete') {
                this.form.id = data.id;
            }
        }
    }"
>
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Employees table</h1>

        <!-- Search -->
        <div class="search-bar flex items-center flex-initial w-sm px-5 py-2 ml-auto border-solid border-2 border-gray-300 bg-gray-100 rounded-lg">
            <input type="text" name="search-input" id="search-input" class="search-input bg-gray-100 p-0 w-full border-none outline-none focus:ring-0" placeholder="Search...">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
        </div>

        <button class="bg-blue-600 text-white hover:bg-blue-700 rounded-md cursor-pointer px-3 py-2"><i class="fa-solid fa-file-import"></i> Import</button>
        <button class="bg-blue-600 text-white hover:bg-blue-700 rounded-md cursor-pointer px-3 py-2"><i class="fa-solid fa-file-export"></i> Export</button>
        <i @click="openModal('create')" class="fa-solid fa-user-plus cursor-pointer hover:scale-120"></i>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Company ID</th>
                    <th>Full Name</th>
                    <th>Farm</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                    <tr>
                        <td>{{$employee->company_id}}</td>
                        <td>{{$employee->full_name}}</td>
                        <td>{{$employee->farm}}</td>
                        <td>{{$employee->position}}</td>
                        <td>No Ongoing Pan</td>
                        <td>
                            <i class="fa-solid fa-pen-to-square cursor-pointer hover:scale-120" @click='openModal("edit", {
                                id: {{ $employee->id }},
                                company_id: "{{ $employee->company_id }}",
                                name: "{{ $employee->full_name }}",
                                farm: "{{ $employee->farm }}",
                                position: "{{ $employee->position }}"
                            })'></i>
                            <i class="fa-solid fa-trash cursor-pointer hover:scale-120" @click='openModal("delete", {
                                id: {{ $employee->id }},
                            })'></i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Overlay -->
    <div x-show="modalOpen" class="fixed inset-0 bg-black/50 z-40"></div>

    <!-- Modal -->
    <div
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <div class="bg-white p-6 rounded-lg shadow-lg w-md z-10">
            <template x-if="modalType == 'create'">
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Add Employee</h2>

                    <div class="input-group mb-2">
                        <label>Company ID: </label>
                        <input type="text" wire:model="company_id">
                    </div>

                    <div class="input-group mb-2">
                        <label>Employee Name: </label>
                        <input type="text" wire:model="employee_name">
                    </div>

                    <div class="input-group mb-2">
                        <label>Farm: </label>
                        <select name="" id="" wire:model="employee_farm">
                            <option value="">Select Farm</option>
                            <option value="BFC">BFC</option>
                            <option value="BBG">BBGC</option>
                            <option value="BRD">BROOKDALE</option>
                            <option value="HCF">Hatchery Farm</option>
                            <option value="PFC">PFC</option>
                            <option value="RH">RH</option>
                        </select>
                    </div>

                    <div class="input-group mb-4">
                        <label>Position: </label>
                        <input type="text" wire:model="employee_position">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        <button type="button" @click="modalOpen = false; $wire.createEmployee()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">
                            Confirm
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="modalType == 'edit'">
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Edit Employee Details</h2>

                   <div class="input-group mb-2">
                        <label>Company ID: </label>
                        <input type="text" x-model="form.company_id">
                    </div>

                    <div class="input-group mb-2">
                        <label>Employee Name: </label>
                        <input type="text" x-model="form.name">
                    </div>

                    <div class="input-group mb-2">
                        <label>Farm: </label>
                        <select name="" id="" x-model="form.farm">
                            <option value="">Select Farm</option>
                            <option value="BFC">BFC</option>
                            <option value="BBG">BBGC</option>
                            <option value="BRD">BROOKDALE</option>
                            <option value="HCF">Hatchery Farm</option>
                            <option value="PFC">PFC</option>
                            <option value="RH">RH</option>
                        </select>
                    </div>

                    <div class="input-group mb-4">
                        <label>Position: </label>
                        <input type="text" x-model="form.position">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        <button type="button" @click="modalOpen = false; $wire.updateEmployee(form)" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">
                            Confirm
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="modalType == 'delete'">
                <div>
                    <h2 class="text-xl font-semibold mb-4">Delete Employee</h2>
                    <p class="mb-6">Are you sure you want to delete this employee? Action cannot be undone.</p>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                        <button type="button" @click="modalOpen = false; $wire.deleteEmployee(form.id)" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">
                            Confirm
                        </button>
                    </div>
                </div>
            </template>
        </div>

    </div>

</div>