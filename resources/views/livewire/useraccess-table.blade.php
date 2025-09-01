


<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">User Access</h1>
        <!-- <x-search-sort-filter/> -->
    </div>

    <div class="table-container" 
        x-data="{
            showModal: false,
            modalData: {
                id: '',
                role: '',
                action: '',
                name: '',
                header: '',
                message: ''
            },
            openModal(id, action, role, name = null) {
                this.modalData.id = id;
                this.modalData.action = action;
                this.modalData.role = role;
                this.modalData.name = name;

                this.modalData.header = (action === 'grant' ? 'Grant Access' : 'Revoke Access');
                this.modalData.message = `Are you sure you want to ${action} this user's access to ${role} Module?`;

                this.showModal = true;
            },
        }"
    >
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
                    <th>E-Sign</th>
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
                    @endphp
                    <tr>
                        <td>{{ $user['id'] }}</td>
                        <td>{{ $fullname }}</td>
                        <td class="table-actions">
                            @if($access['RQ_Module'])
                                <button @click="openModal({{$user['id']}},'revoke', 'Requestor')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openModal({{$user['id']}},'grant', 'Requestor', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['DH_Module'])
                                <button @click="openModal({{$user['id']}},'revoke', 'Division Head')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openModal({{$user['id']}},'grant', 'Division Head', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['HRP_Module'])
                                <button @click="openModal({{$user['id']}},'revoke', 'HR Preparer')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openModal({{$user['id']}},'grant', 'HR Preparer', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['HRA_Module'])
                                <button @click="openModal({{$user['id']}},'revoke', 'HR Approver')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openModal({{$user['id']}},'grant', 'HR Approver', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if($access['FA_Module'])
                                <button @click="openModal({{$user['id']}},'revoke', 'Final Approver')" class="border-solid border-3 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">Revoke</button>
                            @else
                                <button @click="openModal({{$user['id']}},'grant', 'Final Approver', '{{$fullname}}')" class="border-solid border-3 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">Grant</button>
                            @endif
                        </td>
                        <td class="table-actions">
                            <button class="border-solid border-3 border-blue-600 bg-blue-600 text-blue-600 text-white">Upload</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Overlay (instant) -->
        <div x-show="showModal" class="fixed inset-0 bg-black/50 z-40"></div>

        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 flex items-center justify-center z-40"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-md z-10">
                <h2 class="text-xl font-semibold mb-4" x-text="modalData.header"></h2>
                <p class="mb-6" x-text="modalData.message"></p>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                    <button type="button" @click="showModal = false; $wire.manageAccess(modalData.id, modalData.action, modalData.role, modalData.name)" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="pagination-container flex justify-end gap-3">
        <a class="px-4 py-2 bg-blue-600 text-white rounded-md hover:scale-110" href="">1</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">2</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">3</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">4</a>
        <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">5</a>
    </div>

</div>