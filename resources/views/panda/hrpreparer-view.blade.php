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
               <a href="#" onclick="window.history.back()"><div></div><i class="fa-solid fa-arrow-right-from-bracket rotate-180"></i></a>
               <a href="#content-block-1" class="active"><div></div><p>RF</p></a>
               <a href="#content-block-2"><div></div><p>PF</p></a>
               <a href="#content-block-3"><div></div><i class="fa-solid fa-file-pen"></i></a>
        </aside>

          <!-- Content Area -->
          <main class="content">
               <section class="content-block relative" id="content-block-1">
                    <livewire:requestor-form mode="view" module="hr_preparer" :requestID="$requestID" />
               </section>
               
               <livewire:preparer-pan module="hr_preparer" :requestID="$requestID"/>
               
               <livewire:preparer-log :requestID="$requestID"/>
          </main>
    </div>

    <script src="{{ asset('js/dashboard-mb.js') }}"></script>
@endsection