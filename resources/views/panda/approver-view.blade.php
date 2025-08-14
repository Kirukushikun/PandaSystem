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
               <a href="/approver"><div></div><i class="fa-solid fa-arrow-right-from-bracket rotate-180"></i></a>
               <a href="#content-block-1" class="active"><div></div><p>RF</p></a>
               <a href="#content-block-2"><div></div><p>PF</p></a>
               <a href="#content-block-3"><div></div><i class="fa-solid fa-file-pen"></i></a>
        </aside>

          <!-- Content Area -->
          <main class="content">
               <section class="content-block relative" id="content-block-1">
                    <div class="form-buttons absolute top-[35px] right-[40px] flex gap-3 justify-end">
                         <div class="request-status bg-orange-100 text-orange-500">For Approval</div>
                         <!-- <button class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to Requestor</button> -->
                    </div>

                    <h1 class="text-[22px]">Request Form</h1>
                    <div class="form-container relative flex flex-col gap-5 h-full">

                         <div class="input-fields grid grid-cols md:grid-cols-2 lg:grid-cols-4 gap-4">
                              <div class="input-group">
                                   <label for="">Employee Name:</label>
                                   <input type="text" readonly>
                              </div>
                              <div class="input-group">
                                   <label for="">Employee ID:</label>
                                   <input type="text" readonly>
                              </div>
                              <div class="input-group">
                                   <label for="">Department:</label>
                                   <input type="text" readonly>
                              </div>
                              <div class="input-group">
                                   <label for="">Type of Action:</label>
                                   <input type="text" readonly>
                              </div>
                         </div>

                         <div class="input-group h-full">
                              <label for="">Justification:</label>
                              <textarea name="" id="" class="w-full h-50 resize-none" readonly></textarea>
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
                    </div>
               </section>

               <section class="content-block" id="content-block-2">
                    <h1 class="text-[22px]">PAN Preparation Form</h1>
                    <div class="form-container relative flex flex-col gap-5 h-full">
                         <div class="input-fields grid grid-cols md:grid-cols-2 lg:grid-cols-4 gap-4">
                              <div class="input-group">
                                   <label for="">Date Hired:</label>
                                   <input type="date" readonly>
                              </div>
                              <div class="input-group">
                                   <label for="">Employement Status:</label>
                                   <input type="text" readonly>
                              </div>
                              <div class="input-group">
                                   <label for="">Division:</label>
                                   <input type="text" readonly>
                              </div>
                              <div class="input-group">
                                   <label for="">Date of Effectivity:</label>
                                   <input type="date" readonly>
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
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Section</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Place of Assignment</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Immediate Head</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Position</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Job Level</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Basic</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                                  <td class="border-t-2 border-gray-300 text-center">Meal Allowance</td>
                                                  <td class="border-t-2 border-gray-300">
                                                       <input type="text" class="w-full border-none focus:ring-0 text-center outline-none" readonly />
                                                  </td>
                                             </tr>
                                        </tbody>
                                   </table>
                              </div>
                         </div>

                         <div class="input-group">
                              <label for="">Remarks and Other Consideration:</label>
                              <textarea name="" id="" class="w-full h-30 resize-none" readonly></textarea>
                         </div>

                         <div class="input-group">
                              <label for="">Prepared By:</label>
                              <p>Iverson Guno</p>
                         </div>

                         <!-- Alpine instance -->
                         <div x-data="{ showModal: false, modalMessage: '' }">

                              <!-- Buttons -->
                              <div class="form-buttons bottom-0 right-0 flex gap-3 justify-end md:mb-0 md:absolute">
                                   <button @click="modalMessage = 'Are you sure you want to submit?'; showModal = true" class="border border-3 border-gray-600 bg-gray-600 text-white hover:bg-gray-800 px-4 py-2">Approve</button>
                                   <button @click="modalMessage = 'Save this as a draft?'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Reject</button>
                                   <button @click="modalMessage = 'Reset the form?'; showModal = true" class="border-3 border-gray-600 text-gray-600 px-4 py-2 transition-colors duration-300 hover:bg-gray-200">Return to HR</button>
                              </div>

                              <!-- Overlay (instant) -->
                              <div x-show="showModal" class="fixed inset-0 bg-black/50 z-40"></div>

                              <!-- Modal box (with transition) -->
                              <div
                                   x-show="showModal"
                                   x-transition:enter="transition ease-out duration-200"
                                   x-transition:enter-start="opacity-0 scale-90"
                                   x-transition:enter-end="opacity-100 scale-100"
                                   x-transition:leave="transition ease-in duration-150"
                                   x-transition:leave-start="opacity-100 scale-100"
                                   x-transition:leave-end="opacity-0 scale-90"
                                   class="fixed inset-0 flex items-center justify-center z-50"
                              >
                                   <div class="bg-white p-6 rounded-lg shadow-lg w-96 z-10">
                                        <h2 class="text-lg font-semibold mb-4">Confirmation</h2>
                                        <p class="mb-6" x-text="modalMessage"></p>
                                        <div class="flex justify-end gap-3">
                                        <button @click="showModal = false" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-100 cursor-pointer">Cancel</button>
                                        <button @click="showModal = false" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-800 cursor-pointer">Confirm</button>
                                        </div>
                                   </div>
                              </div>
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