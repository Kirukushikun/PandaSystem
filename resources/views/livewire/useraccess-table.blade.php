<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">User Access</h1>
    </div>

    <div class="table-container" 
        x-data="{
            modalOpen: false,
            modalType: 'confirm', // confirm | edit | department
            modalData: {},

            openConfirm(id, action, role, name = null) {
                this.modalType = 'confirm';
                this.modalData = {
                    id,
                    action,
                    role,
                    name,
                    header: action === 'grant' ? 'Grant Access' : 'Revoke Access',
                    message: `Are you sure you want to ${action} this user's access to ${role} Module?`
                };
                this.modalOpen = true;
            },

            openEdit(user) {
                this.modalType = 'edit';
                this.modalData = {
                    id: user.id,
                    farm: user.farm ?? '',
                    position: user.position ?? '',
                    role: user.role ?? '',
                    isConfidentialityApprover: user.isConfidentialityApprover ?? false
                };
                this.modalOpen = true;
            },

            openDepartments(user) {
                this.modalType = 'department';
                this.modalData = {
                    id: user.id,
                    name: user.name,
                    departmentIds: user.departmentIds ?? [],
                    headDepartmentIds: user.headDepartmentIds ?? []
                };
                this.modalOpen = true;
            }
        }"
    >
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Farm</th>
                    <th>Position</th>
                    <th>Departments</th>
                    <th>RQ Module</th>
                    <th>DH Module</th>
                    <th>HRP Module</th>
                    <th>HRA Module</th>
                    <th>FA Module</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    @php
                        $dbUser = $dbUsers[$user['id']] ?? null;
                        $access = $dbUser->access ?? [
                            'RQ_Module' => false,
                            'DH_Module' => false,
                            'HRP_Module' => false,
                            'HRA_Module' => false,
                            'FA_Module' => false
                        ];
                        $fullname = $user['first_name'] . ' ' . $user['last_name'];
                        $userDepartments = $dbUser?->departments ?? collect();
                        $headDepartments = $dbUser?->headedDepartments ?? collect();
                    @endphp
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $fullname }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>
                            {{$dbUser->farm ?? '--'}}
                        </td>
                        <td>
                            {{$dbUser->position ?? '--'}}
                        </td>
                        <td>
                            <div x-data class="flex flex-col gap-1">
                                @forelse($userDepartments as $dept)
                                    <span class="text-xs">{{ $dept->name }}</span>
                                @empty
                                    <span class="text-xs text-gray-400">--</span>
                                @endforelse

                                @if($headDepartments->isNotEmpty())
                                    <span class="text-xs font-semibold text-blue-600">Head: {{ $headDepartments->pluck('name')->implode(', ') }}</span>
                                @endif

                                <i @click="openDepartments({
                                        id: {{ $user['id'] }},
                                        name: '{{ $fullname }}',
                                        departmentIds: {{ $userDepartments->pluck('id')->values()->toJson() }},
                                        headDepartmentIds: {{ $headDepartments->pluck('id')->values()->toJson() }}
                                    })" class="fa-solid fa-pen-to-square text-gray-500 cursor-pointer"></i>
                            </div>
                        </td>
                        <td class="table-actions">
                            @if($access['RQ_Module'])
                                <button @click="openConfirm({{$user['id']}},'revoke', 'Requestor')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openConfirm({{$user['id']}},'grant', 'Requestor', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['DH_Module'])
                                <button @click="openConfirm({{$user['id']}},'revoke', 'Division Head')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openConfirm({{$user['id']}},'grant', 'Division Head', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['HRP_Module'])
                                <button @click="openConfirm({{$user['id']}},'revoke', 'HR Preparer')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openConfirm({{$user['id']}},'grant', 'HR Preparer', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['HRA_Module'])
                                <button @click="openConfirm({{$user['id']}},'revoke', 'HR Approver')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openConfirm({{$user['id']}},'grant', 'HR Approver', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['FA_Module'])
                                <button @click="openConfirm({{$user['id']}},'revoke', 'Final Approver')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openConfirm({{$user['id']}},'grant', 'Final Approver', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            <div x-data>
                                @php
                                    $dbUser = $dbUsers[$user['id']] ?? null;
                                @endphp

                                @if($dbUser) {{-- User exists in local system --}}
                                    @if($dbUser->esign) {{-- Has existing e-sign --}}
                                        <input type="file" x-ref="fileInput" accept="image/*" class="hidden"
                                            wire:model="esignUpload"
                                            @change="$wire.currentUserId = '{{ $user['id'] }}'; $wire.uploadEsign('{{ $user['id'] }}')">

                                        <button type="button"
                                                @click="$refs.fileInput.click()"
                                                class="border-3 border-blue-600 bg-blue-600 text-white px-3 py-1 rounded">
                                            Re-upload
                                        </button>

                                        <i class="fa-solid fa-eye text-gray-500"
                                            onclick="window.open('{{ asset('storage/' . $dbUser->esign) }}', '_blank')">
                                        </i>

                                        <i @click="openEdit({ id: {{ $user['id'] }}, farm: '{{ $dbUser->farm ?? '' }}', position: '{{ $dbUser->position ?? '' }}', role: '{{ $dbUser->role ?? '' }}', isConfidentialityApprover: {{ $dbUser->is_confidentiality_approver ? 'true' : 'false' }} })" class="fa-solid fa-pen-to-square text-gray-500"></i>
                                    @else {{-- User exists but no e-sign yet --}}
                                        <input type="file" x-ref="fileInput" accept="image/*" class="hidden"
                                            wire:model="esignUpload"
                                            @change="$wire.currentUserId = '{{ $user['id'] }}'; $wire.uploadEsign('{{ $user['id'] }}')">

                                        <button type="button"
                                                @click="$refs.fileInput.click()"
                                                class="border-3 border-blue-600 bg-blue-600 text-white px-3 py-1 rounded">
                                            Upload
                                        </button>

                                        <i @click="openEdit({ id: {{ $user['id'] }}, farm: '{{ $dbUser->farm ?? '' }}', position: '{{ $dbUser->position ?? '' }}', role: '{{ $dbUser->role ?? '' }}', isConfidentialityApprover: {{ $dbUser->is_confidentiality_approver ? 'true' : 'false' }} })" class="fa-solid fa-pen-to-square text-gray-500"></i>
                                        
                                    @endif
                                @else {{-- User does not exist locally --}}
                                    <button type="button" class="border-3 border-gray-500 bg-gray-500 text-white px-3 py-1 rounded" disabled>
                                        Upload
                                    </button>

                                    <i class="fa-solid fa-pen-to-square text-gray-500"></i>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

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
            <div class="bg-white p-6 rounded-lg shadow-lg z-10" :class="modalType === 'department' ? 'w-auto' : 'w-md'">

                <!-- Confirm Modal -->
                <template x-if="modalType === 'confirm'">
                    <div>
                        <h2 class="text-xl font-semibold mb-4" x-text="modalData.header"></h2>
                        <p class="mb-6" x-text="modalData.message"></p>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                            <button type="button"
                                @click="modalOpen = false; $wire.manageAccess(modalData.id, modalData.action, modalData.role, modalData.name)"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">
                                Confirm
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Edit Modal -->
                <template x-if="modalType === 'edit'">
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Edit User Info</h2>

                        <div class="mb-4">
                            <label class="block mb-1 font-medium">Farm</label>
                            <select class="border w-full rounded-md px-2 py-1"
                                    x-model="modalData.farm">
                                <option value="">Select Farm</option>
                                <option value="BFC">BFC</option>
                                <option value="BDL">BDL</option>
                                <option value="PFC">PFC</option>
                                <option value="RH">RH</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1 font-medium">Position</label>
                            <input type="text" class="border w-full rounded-md px-2 py-1"
                                x-model="modalData.position">
                        </div>

                        <div class="mb-4">
                            <label class="block mb-1 font-medium">Role</label>
                            <select class="border w-full rounded-md px-2 py-1"
                                    x-model="modalData.role">
                                <option value="">Unset</option>
                                <option value="hrhead">HR Head</option>
                                <option value="admin">ADMIN</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" x-model="modalData.isConfidentialityApprover">
                                <span class="font-medium">Confidentiality Approver</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Sees and confirms Manila-tagged PANs from every department. Regular division heads never see Manila PANs, even for their own department.</p>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                            <button type="button"
                                @click="modalOpen = false; $wire.updateUser(modalData)"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">
                                Save
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Departments Modal -->
                <template x-if="modalType === 'department'">
                    <div class="w-[700px]">
                        <h2 class="text-xl font-semibold" x-text="modalData.name"></h2>
                        <p class="text-sm text-gray-500 mb-4">Requestor and Head are independent — a user can head a department without being a requestor for it (e.g. an overall approver of division heads), and a department can have more than one head.</p>

                        <div class="border rounded-md overflow-hidden mb-4">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500">
                                    <tr>
                                        <th class="text-left font-medium px-3 py-2">Department</th>
                                        <th class="font-medium px-3 py-2 w-28">
                                            Requestor
                                            <button type="button" class="block w-full text-[11px] font-normal text-blue-600 hover:underline"
                                                @click="modalData.departmentIds = modalData.departmentIds.length === {{ $departments->count() }} ? [] : {{ $departments->pluck('id')->values()->toJson() }}"
                                                x-text="modalData.departmentIds.length === {{ $departments->count() }} ? 'Clear all' : 'Select all'"></button>
                                        </th>
                                        <th class="font-medium px-3 py-2 w-28">
                                            Head
                                            <button type="button" class="block w-full text-[11px] font-normal text-blue-600 hover:underline"
                                                @click="modalData.headDepartmentIds = modalData.headDepartmentIds.length === {{ $departments->count() }} ? [] : {{ $departments->pluck('id')->values()->toJson() }}"
                                                x-text="modalData.headDepartmentIds.length === {{ $departments->count() }} ? 'Clear all' : 'Select all'"></button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($departments as $dept)
                                        @php $currentHeads = $dept->heads->pluck('name')->implode(', '); @endphp
                                        <tr class="border-t">
                                            <td class="px-3 py-2">
                                                {{ $dept->name }}
                                                @if($currentHeads)
                                                    <div class="text-xs text-gray-400">Head: {{ $currentHeads }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center px-3 py-2">
                                                <input type="checkbox" value="{{ $dept->id }}" x-model.number="modalData.departmentIds">
                                            </td>
                                            <td class="text-center px-3 py-2">
                                                <input type="checkbox" value="{{ $dept->id }}" x-model.number="modalData.headDepartmentIds">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 border rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                            <button type="button"
                                @click="modalOpen = false; $wire.saveDepartments(modalData.id, modalData.name, modalData.departmentIds, modalData.headDepartmentIds)"
                                class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">
                                Save
                            </button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

</div>