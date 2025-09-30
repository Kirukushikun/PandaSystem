@extends('layouts.app')

@push('styles')
     <style>
          /* Content */
          .content {
          width: 100%;
          overflow-x: hidden;
          scroll-behavior: smooth;
          }

          /* Content Blocks */
          .content-block {
          display: none;
          }
          .content-block.active {
          border-radius: 10px;
          background-color: var(--container-color);
          padding: 30px;
          display: flex;
          flex-direction: column;
          gap: 20px;
          height: 100%;
          }
          .content-block::-webkit-scrollbar {
          width: 15px;
          }
          .content-block::-webkit-scrollbar-track {
          background: var(--scrollbar-track);
          border-radius: 6px;
          }
          .content-block::-webkit-scrollbar-thumb {
          background: var(--scrollbar-thumb);
          border-radius: 6px;
          cursor: pointer;
          }
     </style>
@endpush

@section('content')
    <!-- Header -->
     <x-header/>

     <!-- Layout -->
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
               <a href="#" onclick="window.history.back()"><div></div><i class="fa-solid fa-arrow-right-from-bracket rotate-180"></i></a>
               <!-- <a href="#content-block-1" class="active"><div></div><p>RF</p></a>
               <a href="#content-block-2"><div></div><p>PF</p></a>
               <a href="#content-block-3"><div></div><i class="fa-solid fa-file-pen"></i></a> -->
        </aside>

          <!-- Content Area -->
          <main class="content">
               <div class="content-block active">
                    <!-- Employee Header -->
                    <div class="">
                         <div class="text-gray-700">
                              <span class="font-semibold">ID:</span> {{$employee->company_id}} | 
                              <span class="font-semibold">Employee:</span> {{$employee->full_name}} | 
                              <span class="font-semibold">Position:</span> {{$employee->position}} |
                              <span class="font-semibold">Farm:</span> {{$employee->farm}} |
                              <span class="font-semibold">Department:</span> {{$employee->department}}
                         </div>
                    </div>

                    <!-- PAN Record -->
                    @forelse($requestRecords as $record)
                         <div class="bg-white shadow rounded-xl p-5 border border-gray-200 flex justify-between items-center">
                              <div>
                                   <h2 class="text-base font-bold text-gray-800">
                                        {{$record->request_no}} <span class="font-normal text-gray-500">| {{$record->type_of_action}}</span>
                                   </h2>
                                   <p class="text-sm text-gray-600 capitalize">
                                        <span class="font-semibold">Confidentiality:</span> {{$record->confidentiality}}
                                   </p>
                                   <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Submitted:</span> {{$record->submitted_at->format('M Y')}}
                                   </p>
                              </div>
                              <div class="flex items-center gap-3">
                                   <span class="px-4 py-2 text-sm font-medium bg-green-100 text-green-700 rounded-lg">{{$record->request_status}}</span>
                                   @if($record->confidentiality == 'tarlac' && Auth::user()->role != 'hrhead')
                                        <button class="bg-gray-700 text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 cursor-pointer" onclick="window.location.href='/print-view?requestID={{ encrypt($record->id) }}'">View PDF</button>
                                        <button class="border border-gray-300 text-sm px-4 py-2 rounded-md hover:bg-gray-100 cursor-pointer" onclick="window.location.href='/hrpreparer-view?requestID={{ encrypt($record->id) }}'">Details</button>
                                   @elseif(Auth::user()->role == 'hrhead')
                                        <button class="bg-gray-700 text-white text-sm px-4 py-2 rounded-md hover:bg-gray-800 cursor-pointer" onclick="window.location.href='/print-view?requestID={{ encrypt($record->id) }}'">View PDF</button>
                                        <button class="border border-gray-300 text-sm px-4 py-2 rounded-md hover:bg-gray-100 cursor-pointer" onclick="window.location.href='/hrpreparer-view?requestID={{ encrypt($record->id) }}'">Details</button>
                                   @endif
                              </div>
                         </div>
                    @empty
                         <div class="empty-promt w-full h-full flex flex-col items-center justify-center rounded text-gray-400 select-none">
                              <i class="fa-solid fa-box-open text-xl mb-1"></i>
                              <p class="text-lg">This employee don't have a PAN record yet</p>
                         </div>
                    @endforelse

               </div>
          </main>
    </div>

@endsection