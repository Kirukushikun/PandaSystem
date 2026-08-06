@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex items-center justify-center bg-gray-50 px-4">
        <div class="flex flex-col items-center text-center gap-5 max-w-md">
            <img src="{{ asset('BGC-logo.png') }}" alt="Brookside Group of Companies" class="h-28 w-auto">

            <div class="h-px w-12 bg-gray-200"></div>

            <div class="flex flex-col items-center gap-2">
                <h1 class="text-xl font-bold text-gray-800">The Requestor module has moved</h1>
                <p class="text-sm text-gray-500 leading-relaxed">
                    New and existing PAN requests are now filed through <span class="font-semibold text-gray-700">PAN System v2</span>.
                    Sign in there using the same account you use here.
                </p>
            </div>

            <a href="https://pansystemv2.bfcgroup.ph/" target="_blank"
                class="bg-gray-700 text-white text-sm font-medium px-6 py-2.5 rounded-md hover:bg-gray-800 transition-colors cursor-pointer">
                Go to PAN System v2
            </a>
        </div>
    </div>
@endsection
