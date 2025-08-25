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
          <!-- <aside class="sidebar">
               <a href="#content-block-1"><div></div><i class="fa-solid fa-table"></i></a>
               <a href="#content-block-2"><div></div><i class="fa-solid fa-file-invoice"></i></a>
          </aside> -->

          <!-- Content Area -->
          <main class="content">
               <section class="content-block" id="content-block-1">
                    <livewire:divisionhead-table />
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