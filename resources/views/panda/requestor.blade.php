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
               <a href="#content-block-2"><div></div><i class="fa-solid fa-file-invoice"></i></a>
          </aside>

          <!-- Content Area -->
          <main class="content">
               <section class="content-block" id="content-block-1">
                    <livewire:requestor-table />
               </section>

               <section class="content-block overflow-x-hidden" id="content-block-2">
                    <livewire:requestor-form mode="create" module="requestor" />
               </section>

               <!-- <section class="content-block" id="content-block-3">
               Content-block 3
               </section> -->
          </main>
     </div>

     <script src="{{ asset('js/dashboard-sb.js') }}"></script>
@endsection