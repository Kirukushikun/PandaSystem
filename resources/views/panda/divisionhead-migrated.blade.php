@extends('layouts.app')

@section('content')
    <div class="min-h-screen w-full flex items-center justify-center bg-gray-50 px-4">
        <div class="flex flex-col items-center text-center gap-7 max-w-lg">
            <img src="{{ asset('BGC-logo.png') }}" alt="Brookside Group of Companies" class="h-36 w-auto">

            <div class="h-px w-16 bg-gray-200"></div>

            <div class="flex flex-col items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-800">The Division Head module has moved</h1>
                <p class="text-base text-gray-500 leading-relaxed">
                    PAN approvals are now handled through <span class="font-semibold text-gray-700">PAN System v2</span>.
                    Sign in there using the same account you use here.
                </p>
            </div>

            <a href="https://pansystemv2.bfcgroup.ph/" target="_blank"
                class="bg-gray-700 text-white text-base font-medium px-8 py-3 rounded-md hover:bg-gray-800 transition-colors cursor-pointer">
                Go to PAN System v2
            </a>

            <p class="text-sm text-gray-400">
                Don't have access yet? Message IT Admin privately on
                <a href="viber://chat?number=%2B639851416343" class="font-medium text-gray-500 underline hover:text-gray-700">Viber</a>
                (+63 985 141 6343) to request it.
            </p>
        </div>
    </div>
@endsection
