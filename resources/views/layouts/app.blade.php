<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1" />
     <link rel="icon" href="{{ asset('/images/PANDA.ico') }}" type="image/x-icon">
     <!-- <title>@yield('title')</title> -->
      
     <title>PAN System</title>
     <meta name="csrf-token" content="{{ csrf_token() }}">     

     <!-- Styling -->
     @vite(['resources/css/app.css'])
     @fluxAppearance
     <link rel="stylesheet" href="{{ asset('css/general.css') }} ">
     @stack('styles')
     <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

     <!-- Fonts & Icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap">

    <!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
</head>
<body>    
     
     <x-loader/>

     @if(Auth::check() && !request()->is('print-view*'))
          @if(Auth::user()->role == 'admin')
               <div class="navigator absolute flex flex-col gap-2 p-5 rounded-md text-gray-600 text-xl font-bold" style="top: 5px; left: 280px">
                    @if(request()->is('requestor*'))
                         <div>
                              Requestor Module
                         </div>
                    @elseif(request()->is('divisionhead*'))
                         <div>
                              Division Head Module
                         </div>
                    @elseif(request()->is('hrpreparer*'))
                         <div>
                              HR Preparer Module
                         </div>
                    @elseif(request()->is('hrapprover*'))
                         <div>
                              HR Approver Module
                         </div>
                    @elseif(request()->is('approver*'))
                         <div>
                              Final Apporver Module
                         </div>
                    @endif
               </div>
          @endif
     @endif
     
     <div x-data="{ show: false, type: '', header: '', message: '' }" 
          x-init="
               @if(session('notif'))
                    // Handle reload flash notification
                    type = '{{ session('notif.type') }}';
                    header = '{{ session('notif.header') }}';
                    message = '{{ session('notif.message') }}';
                    setTimeout(() => {
                         show = true;
                         setTimeout(() => show = false, 4000);
                    }, 1000);
               @endif

               // Handle SPA/Livewire notification
               window.addEventListener('notif', (event) => {
                    console.log(event.detail); // 👈 check what you actually receive
                    let data = event.detail
                    type = data.type
                    header = data.header
                    message = data.message
                    show = true
                    setTimeout(() => {
                         setTimeout(() => show = false, 4000)
                    }, 1000)
               })
          "
          class="absolute top-10 right-10 z-50">

          <div x-show="show"
               x-transition:enter="transition transform ease-out duration-500"
               x-transition:enter-start="-translate-y-5 opacity-0"
               x-transition:enter-end="translate-y-0 opacity-100"
               x-transition:leave="transition transform ease-in duration-500"
               x-transition:leave-start="translate-y-0 opacity-100"
               x-transition:leave-end="-translate-y-5 opacity-0"
               class="notification w-auto bg-white flex flex-col px-15 py-7 whitespace-nowrap rounded-lg border-solid shadow-xl z-50">

               <div class="notif-header font-bold text-lg flex items-center relative">
                    <i x-show="type == 'success'" class="fa-regular fa-circle-check absolute -left-8 text-green-500 text-xl"></i>
                    <i x-show="type == 'failed'" class="fa-regular fa-circle-xmark absolute -left-8 text-red-500 text-xl"></i>
                    <span x-text="header"></span>
                    <i class="fa-solid fa-xmark absolute -right-8 text-gray-500 hover:text-gray-800 text-xl cursor-pointer" @click="show = false"></i>
               </div>
               
               <div class="notif-body text-md text-gray-500" x-text="message">
               </div>
          </div>
     </div>
     

     @yield('content')

     @fluxScripts
     @livewireScripts
     <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
