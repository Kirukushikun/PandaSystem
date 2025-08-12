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
            <a href="#content-block-1" class="active"><div></div><p>1</p></a>
            <a href="#content-block-2"><div></div><p>2</p></a>
            <a href="#content-block-3"><div></div><p>3</p></a>
        </aside>

        <!-- Content Area -->
        <main class="content">
            <section class="content-block" id="content-block-1">
               <h1 class="text-[22px]">Request Form</h1>
                <livewire:preparer-form />
            </section>
            <section class="content-block" id="content-block-2">
                Content-block 2
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