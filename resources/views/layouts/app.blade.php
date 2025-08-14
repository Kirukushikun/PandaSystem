<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1" />
     <link rel="icon" href="{{ asset('clipboard.ico') }}" type="image/x-icon">
     <!-- <title>@yield('title')</title> -->
      
     <title>PANDA System</title>
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
     <div class="navigator absolute left-[20px] top-[40px] flex flex-col gap-2 bg-white p-5 rounded-md">
          <div class="label">Admin Navigator</div>
          <div class="navigations flex flex-col gap-2">
               <a href="/requestor"
                    class="px-4 py-2 text-white hover:bg-blue-400 {{ request()->is('requestor*') ? 'bg-blue-700' : 'bg-blue-400' }}">
                    Requestor
               </a>

               <a href="/preparer"
                    class="px-4 py-2 text-white hover:bg-blue-400 {{ request()->is('preparer*') ? 'bg-blue-700' : 'bg-blue-400' }}">
                    Preparer
               </a>

               <a href="/approver"
                    class="px-4 py-2 text-white hover:bg-blue-400 {{ request()->is('approver*') ? 'bg-blue-700' : 'bg-blue-400' }}">
                    Approver
               </a>

               <a href="/admin"
                    class="px-4 py-2 text-white hover:bg-blue-400 {{ request()->is('admin*') ? 'bg-blue-700' : 'bg-blue-400' }}">
                    Admin
               </a>
          </div>
     </div>
     @yield('content')
     @fluxScripts
     <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
