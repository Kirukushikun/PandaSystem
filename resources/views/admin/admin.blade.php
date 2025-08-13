@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-sb.css') }}">
@endpush

@section('content')
    <!-- Header -->
    <x-header/>

    <!-- Layout -->
     <div class="layout">
          
          <!-- Sidebar -->
          <aside class="sidebar">
               <a href="#content-block-1"><div></div><i class="fa-solid fa-table"></i></a>
               <a href="#content-block-2"><div></div><i class="fa-solid fa-table"></i></a>
               <a href="#content-block-3"><div></div><i class="fa-solid fa-table"></i></a>
          </aside>

          <!-- Content Area -->
          <main class="content">
               <section class="content-block" id="content-block-1">
                    <h1 class="text-[22px]">My Requests</h1>
                    <div class="table-container">
                         <table>
                              <thead>
                                   <tr>
                                        <th>Request No</th>
                                        <th>Employee Name</th>
                                        <th>Type of Action</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                        <th>Last Update</th>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @php
                                        $statuses = [
                                        'Draft' => 'bg-gray-100 text-gray-500',
                                        'For Prep' => 'bg-blue-100 text-blue-500',
                                        'Returned' => 'bg-yellow-100 text-yellow-500',
                                        'For Approval' => 'bg-orange-100 text-orange-500',
                                        'Rejected' => 'bg-red-100 text-red-500',
                                        'Approved' => 'bg-green-100 text-green-500',
                                        ];
                                   @endphp
                                   @for($i = 0; $i < 9; $i++)
                                        @php
                                        $statusText = array_rand($statuses);
                                        $statusColor = $statuses[$statusText];
                                        @endphp
                                        <tr>
                                        <td>PAN-2025-001</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>Promotion</td>
                                        <td>09/12/2025</td>
                                        <td>
                                                  <div class="status-tag {{ $statusColor }}">{{ $statusText }}</div>
                                        </td>
                                        <td>09/12/2025</td>
                                        <td class="table-actions">
                                                  <button class="bg-blue-600 text-white" onclick="window.location.href='/requestor-view'">View</button>
                                                  <i class="fa-solid fa-box-archive"></i>
                                        </td>
                                        </tr>
                                   @endfor
                              </tbody>
                         </table>
                    </div>
                    <div class="pagination-container flex justify-end gap-3">
                         <a class="px-4 py-2 bg-blue-600 text-white rounded-md hover:scale-110" href="">1</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">2</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">3</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">4</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">5</a>
                    </div>
               </section>
               <section class="content-block" id="content-block-2">
                    <h1 class="text-[22px]">My Requests</h1>
                    <div class="table-container">
                         <table>
                              <thead>
                                   <tr>
                                        <th>Request No</th>
                                        <th>Employee Name</th>
                                        <th>Type of Action</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                        <th>Last Update</th>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @php
                                        $statuses = [
                                        'Draft' => 'bg-gray-100 text-gray-500',
                                        'For Prep' => 'bg-blue-100 text-blue-500',
                                        'Returned' => 'bg-yellow-100 text-yellow-500',
                                        'For Approval' => 'bg-orange-100 text-orange-500',
                                        'Rejected' => 'bg-red-100 text-red-500',
                                        'Approved' => 'bg-green-100 text-green-500',
                                        ];
                                   @endphp
                                   @for($i = 0; $i < 9; $i++)
                                        @php
                                        $statusText = array_rand($statuses);
                                        $statusColor = $statuses[$statusText];
                                        @endphp
                                        <tr>
                                        <td>PAN-2025-001</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>Promotion</td>
                                        <td>09/12/2025</td>
                                        <td>
                                                  <div class="status-tag {{ $statusColor }}">{{ $statusText }}</div>
                                        </td>
                                        <td>09/12/2025</td>
                                        <td class="table-actions">
                                                  <button class="bg-blue-600 text-white" onclick="window.location.href='/requestor-view'">View</button>
                                                  <i class="fa-solid fa-box-archive"></i>
                                        </td>
                                        </tr>
                                   @endfor
                              </tbody>
                         </table>
                    </div>
                    <div class="pagination-container flex justify-end gap-3">
                         <a class="px-4 py-2 bg-blue-600 text-white rounded-md hover:scale-110" href="">1</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">2</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">3</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">4</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">5</a>
                    </div>
               </section>
               <section class="content-block" id="content-block-3">
                    <h1 class="text-[22px]">My Requests</h1>
                    <div class="table-container">
                         <table>
                              <thead>
                                   <tr>
                                        <th>Request No</th>
                                        <th>Employee Name</th>
                                        <th>Type of Action</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                        <th>Last Update</th>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   @php
                                        $statuses = [
                                        'Draft' => 'bg-gray-100 text-gray-500',
                                        'For Prep' => 'bg-blue-100 text-blue-500',
                                        'Returned' => 'bg-yellow-100 text-yellow-500',
                                        'For Approval' => 'bg-orange-100 text-orange-500',
                                        'Rejected' => 'bg-red-100 text-red-500',
                                        'Approved' => 'bg-green-100 text-green-500',
                                        ];
                                   @endphp
                                   @for($i = 0; $i < 9; $i++)
                                        @php
                                        $statusText = array_rand($statuses);
                                        $statusColor = $statuses[$statusText];
                                        @endphp
                                        <tr>
                                        <td>PAN-2025-001</td>
                                        <td>Juan Dela Cruz</td>
                                        <td>Promotion</td>
                                        <td>09/12/2025</td>
                                        <td>
                                                  <div class="status-tag {{ $statusColor }}">{{ $statusText }}</div>
                                        </td>
                                        <td>09/12/2025</td>
                                        <td class="table-actions">
                                                  <button class="bg-blue-600 text-white" onclick="window.location.href='/requestor-view'">View</button>
                                                  <i class="fa-solid fa-box-archive"></i>
                                        </td>
                                        </tr>
                                   @endfor
                              </tbody>
                         </table>
                    </div>
                    <div class="pagination-container flex justify-end gap-3">
                         <a class="px-4 py-2 bg-blue-600 text-white rounded-md hover:scale-110" href="">1</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">2</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">3</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">4</a>
                         <a class="px-4 py-2 bg-blue-100 text-blue-600 rounded-md hover:scale-110" href="">5</a>
                    </div>
               </section>
          </main>
     </div>

     <script>
          document.addEventListener("DOMContentLoaded", function () {
               const sidebarLinks = document.querySelectorAll(".sidebar a");
               const contentBlocks = document.querySelectorAll(".content-block");

               // Restore last active section from localStorage, or default to #content-block-1
               const savedSectionId = localStorage.getItem("activeSection");
               let targetBlock = savedSectionId ? document.querySelector(savedSectionId) : null;

               // If no saved section or it doesn't exist, fall back to default
               if (!targetBlock) {
                    targetBlock = document.querySelector("#content-block-1");
               }

               if (targetBlock) {
                    activateSection(targetBlock);
               }

               sidebarLinks.forEach(link => {
                    link.addEventListener("click", function (event) {
                         event.preventDefault();
                         const href = this.getAttribute("href");
                         const targetBlock = document.querySelector(href);

                         // Save to localStorage
                         localStorage.setItem("activeSection", href);

                         activateSection(targetBlock);
                    });
               });

               function activateSection(targetBlock) {
                    sidebarLinks.forEach(link => link.classList.remove("active"));
                    contentBlocks.forEach(block => block.classList.remove("active"));

                    if (targetBlock) {
                         const targetSidebarLink = document.querySelector(`.sidebar a[href="#${targetBlock.id}"]`);
                         if (targetSidebarLink) targetSidebarLink.classList.add("active");
                         targetBlock.classList.add("active");
                    }
               }
          });
     </script>
@endsection