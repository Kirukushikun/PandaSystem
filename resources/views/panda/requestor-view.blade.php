@extends('layouts.app')

@push('styles')
     <link rel="stylesheet" href="{{ asset('css/dashboard-mb.css') }}">
@endpush

@section('content')
    <!-- Header -->
     <x-header/>

     <!-- Layout -->
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
               <a href="/requestor"><div></div><i class="fa-solid fa-arrow-right-from-bracket rotate-180"></i></a>
        </aside>

          <!-- Content Area -->
          <main class="content">
               <section class="content-block relative" id="content-block-1">
                    <div class="form-buttons absolute top-[35px] right-[40px] flex gap-3 justify-end">
                         <div class="request-status bg-yellow-100 text-yellow-500">Returned</div>
                         <!-- <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button> -->
                    </div>

                    <h1 class="text-[22px]">Request Form</h1>
                    <div class="form-container relative flex flex-col gap-5 h-full">

                         <div class="input-fields grid grid-cols-4 gap-4">
                              <div class="input-group">
                                   <label for="">Employee Name:</label>
                                   <input type="text">
                              </div>
                              <div class="input-group">
                                   <label for="">Employee ID:</label>
                                   <input type="text">
                              </div>
                              <div class="input-group">
                                   <label for="">Department:</label>
                                   <input type="text">
                              </div>
                              <div class="input-group">
                                   <label for="">Type of Action:</label>
                                   <input type="text">
                              </div>
                         </div>

                         <div class="input-group h-full">
                              <label for="">Justification:</label>
                              <textarea name="" id="" class="w-full h-50 resize-none"></textarea>
                         </div>

                         <div class="file-group flex flex-col gap-2">
                              <label for="" class="text-[18px]">Supporting Files:</label>
                              <div class="flex w-full border border-gray-600 rounded-md overflow-hidden text-sm">
                                   <!-- Button -->
                                   <button class="bg-gray-600 text-white px-4 py-2.5 cursor-pointer hover:bg-gray-500">
                                        View File
                                   </button>
                                   <!-- File Name -->
                                   <div class="flex-1 bg-gray-50 text-gray-500 px-4 py-2.5">
                                        sample_document.pdf
                                   </div>
                              </div>
                         </div>
                         
                         <div class="input-group">
                              <label for="">Requested By:</label>
                              <p>Iverson Guno</p>
                         </div>

                         <div class="form-buttons absolute bottom-0 right-0 flex gap-3 justify-end">
                              <button class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800">Resubmit to HR</button>
                              <!-- <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reject</button>
                              <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to HR</button> -->
                         </div>
                    </div>
               </section>
               
               <livewire:preparer-log />
          </main>
    </div>

    <script>
          document.addEventListener("DOMContentLoaded", function () {
               // Grab sidebar links and content blocks
               const sidebarLinks = document.querySelectorAll(".sidebar a");
               const contentBlocks = document.querySelectorAll(".content-block");

               // Handle sidebar link clicks
               sidebarLinks.forEach(link => {
                    link.addEventListener("click", event => {
                         const targetBlock = document.querySelector(link.getAttribute("href"));
                         activateSection(targetBlock);
                    });
               });

               // Handle clicks (or pointer downs) directly on content blocks
               contentBlocks.forEach(block => {
                    block.addEventListener("pointerdown", () => {
                         activateSection(block);
                    });
               });

               // Function to handle sidebar and card-label activation
               function activateSection(targetCard) {
                    // Remove 'active' class from all sidebar links and card labels
                    sidebarLinks.forEach(link => link.classList.remove("active"));

                    if (targetCard) {
                         // Activate corresponding sidebar link
                         const targetSidebarLink = document.querySelector(`.sidebar a[href="#${targetCard.id}"]`);
                         if (targetSidebarLink) targetSidebarLink.classList.add("active");
                    }
               }
          });
    </script>
@endsection