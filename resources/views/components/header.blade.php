@if(Auth::check())
<div class="header">
    <div class="logo flex items-center text-sm">
        <img src="{{asset('BGC-logo.png')}}" alt="">
        <h2 class="whitespace-nowrap">| Personnel Action Notice Document Automation</h2>
    </div>
    
    <div x-data="{ open: false, admin: false}" class="profile flex items-center gap-3">
        <div class="details text-right text-sm">
            <div class="name" id="user-name">{{ Auth::user()->name }}</div>
            <div class="email" id="user-email"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                fetch(`https://bfcgroup.ph/api/v1/users/get-details/{{ Auth::user()->id }}`, {
                    method: 'POST',
                    headers: {
                        'x-api-key': '123456789bgc',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Example: If email is also provided
                    if (data.email) {
                        document.getElementById('user-email').textContent = data.email;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        </script>

        @if(Auth()->user()->access['HRP_Module'] == true || Auth()->user()->access['HRA_Module'] == true)
        <div x-data="{open: false}" class="relative">
            <livewire:notifications />
        </div>
        @endif

        <!-- Icon with toggle dropdown -->
        <div @mouseenter="open = true" @mouseleave="open = false" class="relative">
            <i class="fa-solid fa-circle-user text-[30px] cursor-pointer hover:scale-110"></i>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition class="absolute left-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-40">
                <ul class="py-2">
                    <!-- <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                    </li> -->
                    <li>
                        <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200" onclick="window.location.href='/home'" >
                            <i class="fa-solid fa-arrow-right-to-bracket rotate-180 ml-1"></i>
                            Back Home
                        </div>
                    </li>
                    <li>
                        <form x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" method="GET" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 cursor-pointer text-gray-500 hover:text-gray-800 hover:scale-105 hover:bg-gray-100">
                                <template class="ml-1" x-if="!open">
                                    <i class="fa-solid fa-door-closed"></i>
                                </template>
                                <template class="ml-1" x-if="open">
                                    <i class="fa-solid fa-door-open"></i>
                                </template>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        @if(Auth::check() && !request()->is('print-view*'))
            @if(Auth::user()->role == 'admin')
                <div @mouseenter="admin = true" @mouseleave="admin = false" class="relative">
                    <!-- <i class="fa-solid fa-door-open "></i> -->
                    <i class="fa-solid fa-user-tie cursor-pointer hover:scale-110 text-[22px]"></i>

                    <!-- Dropdown Menu -->
                    <div x-show="admin" x-transition class="absolute left-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-40">
                        <ul class="py-2">
                            <li>
                                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200 {{ request()->is('requestor*') ? 'text-gray-800 scale-105 bg-gray-100' : '' }}" onclick="window.location.href='/requestor'" >
                                        <i class="fa-solid fa-circle-right rotate-180"></i>
                                        Requestor
                                    </div>
                                </li>

                                <li>
                                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200 {{ request()->is('divisionhead*') ? 'text-gray-800 scale-105 bg-gray-100' : '' }}" onclick="window.location.href='/divisionhead'" >
                                        <i class="fa-solid fa-circle-right rotate-180"></i>
                                        Division Head
                                    </div>
                                </li>

                                <li>
                                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200 {{ request()->is('hrpreparer*') ? 'text-gray-800 scale-105 bg-gray-100' : '' }}" onclick="window.location.href='/hrpreparer'" >
                                        <i class="fa-solid fa-circle-right rotate-180"></i>
                                        HR Preparer
                                    </div>
                                </li>

                                <li>
                                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200 {{ request()->is('hrapprover*') ? 'text-gray-800 scale-105 bg-gray-100' : '' }}" onclick="window.location.href='/hrapprover'" >
                                        <i class="fa-solid fa-circle-right rotate-180"></i>
                                        HR Approver
                                    </div>
                                </li>

                                <li>
                                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200 {{ request()->is('approver*') ? 'text-gray-800 scale-105 bg-gray-100' : '' }}" onclick="window.location.href='/approver'" >
                                        <i class="fa-solid fa-circle-right rotate-180"></i>
                                        Approver
                                    </div>
                                </li>

                                <li>
                                    <div class="home-btn text-left px-4 py-2 userselect-none cursor-pointer text-gray-500 text-[17px] hover:text-gray-800 hover:scale-105  hover:bg-gray-100 transition-transform duration-200 {{ request()->is('admin*') ? 'text-gray-800 scale-105 bg-gray-100' : '' }}" onclick="window.location.href='/admin'" >
                                        <i class="fa-solid fa-circle-right rotate-180"></i>
                                        Admin
                                    </div>
                                </li>
                        </ul>
                    </div>
                </div>   
            @endif
        @endif

    </div>
</div>
@endif