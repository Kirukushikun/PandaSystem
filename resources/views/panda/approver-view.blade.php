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
               <livewire:preparer-form :requestID="$requestID"/>

               <livewire:preparer-pan :requestID="$requestID" role="approver"/>
               
               <livewire:preparer-log :requestID="$requestID"/>
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