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
               <a href="#content-block-2"><div></div><i class="fa-regular fa-address-book"></i></a>
               <!-- <a href="#content-block-3"><div></div><i class="fa-solid fa-bell"></i></a> -->
          </aside>

          <!-- Content Area -->
          <main class="content">
               <section class="content-block" id="content-block-1">
                    <livewire:hrapprover-table />
               </section>
               <section class="content-block" id="content-block-2">
                    <livewire:panrecords-table module="hrapprover"/>
               </section>
               <!-- <section class="content-block" id="content-block-3">
               </section> -->
          </main>
     </div>

     <script src="{{ asset('js/dashboard-sb.js') }}"></script>
@endsection