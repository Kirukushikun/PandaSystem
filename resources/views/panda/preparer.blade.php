@extends('layouts.app')

@push('styles')
     <link rel="stylesheet" href="{{ asset('css/dashboard-mb.css') }}">
@endpush

@section('content')
    <!-- Header -->
    <div class="header">
        <i class="fa-solid fa-fire logo"></i>
        <h2>STARTER DASHBOARD</h2>
        <i class="fa-solid fa-right-from-bracket exit-icon"></i>
    </div>

     <!-- Layout -->
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
               <!-- <a href=""><div></div><i class="fa-solid fa-arrow-right-from-bracket rotate-180"></i></a> -->
               <a href="#content-block-1" class="active"><div></div><p>RF</p></a>
               <a href="#content-block-2"><div></div><p>PF</p></a>
               <a href="#content-block-3"><div></div><i class="fa-solid fa-file-pen"></i></a>
        </aside>

        <!-- Content Area -->
        <main class="content">
            <section class="content-block" id="content-block-1">
               <h1 class="text-[22px]">Request Form</h1>
               <livewire:preparer-form />
            </section>

            <section class="content-block" id="content-block-2">
               <h1 class="text-[22px]">PAN Preparation Form</h1>
               <div class="form-container relative flex flex-col gap-5 h-full">
                    <div class="input-fields grid grid-cols-4 gap-4">
                         <div class="input-group">
                              <label for="">Date Hired:</label>
                              <input type="text">
                         </div>
                         <div class="input-group">
                              <label for="">Employement Status:</label>
                              <input type="text">
                         </div>
                         <div class="input-group">
                              <label for="">Division:</label>
                              <input type="text">
                         </div>
                         <div class="input-group">
                              <label for="">Date of Effectivity:</label>
                              <input type="text">
                         </div>
                    </div>

                    <div class="table-group w-full flex flex-col gap-3">
                         <label class="text-[18px] mb-2">Action Reference Table:</label>

                         <!-- Outer wrapper for rounding and border -->
                         <div class="overflow-hidden rounded-md border border-2 border-gray-300">
                              <table class="w-full table-fixed border-separate border-spacing-0">
                                   <thead class="bg-white">
                                        <tr>
                                             <th class="w-1/3 border-gray-300 text-center">From</th>
                                             <th class="w-1/3 border-gray-300 text-center">Action Reference</th>
                                             <th class="w-1/3 border-gray-300 text-center">To</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center">Section</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center">Place of Assignment</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center">Immediate Head</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center">Position</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center">Job Level</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center">Basic</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center font-medium">
                                                  <select name="" id="" class="w-full border-none focus:ring-0 text-center outline-none">
                                                       <option value="">Meal Allowance</option>
                                                       <option value="">Transportation Allowance</option>
                                                  </select>
                                             </td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>
                                        <tr>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                             <td class="border-t-2 border-gray-300 text-center font-medium text-blue-500">+ Add Allowance</td>
                                             <td class="border-t-2 border-gray-300">
                                                  <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" />
                                             </td>
                                        </tr>

                                   </tbody>
                              </table>
                         </div>
                    </div>

                    <div class="input-group">
                         <label for="">Remarks and Other Consideration:</label>
                         <textarea name="" id="" class="w-full h-30 resize-none"></textarea>
                    </div>

                    <div class="input-group">
                         <label for="">Prepared By:</label>
                         <p>Iverson Guno</p>
                    </div>

                    <div class="form-buttons absolute bottom-0 right-0 flex gap-3 justify-end">
                         <button class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800">Submit for Approval</button>
                         <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Save as Draft</button>
                         <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reset</button>
                    </div>
               </div>
            </section>
            <section class="content-block" id="content-block-3">
                Content-block 3
            </section>
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