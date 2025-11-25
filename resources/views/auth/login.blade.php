@extends('layouts.app')

@section('content')
<main class="h-screen flex flex-col items-center justify-center">
    <div class="min-w-md mx-auto bg-white p-10 rounded-md shadow">
        <div class="logo flex flex-col text-center items-center justify-center mb-8">
            <img class="border-b-2 pb-3 mb-3" style="width: 190px;" src="{{asset('BGC-logo.png')}}" alt="">
            <!-- <div class="line h-[2px] bg-black"></div> -->
            <h1 class="font-bold text-lg">Personnel Action Notice <br> Document Automation</h1>
        </div>
        <!-- <h1 class="text-[30px] font-bold w-full">Login</h1> -->

        @if ($errors->any())
            <p class="text-red-500 text-center">
                {{ $errors->first('login') }}
            </p>
        @endif

        <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="mt-3 ">
            @csrf

            <input name="email" type="email" placeholder="Email" 
                class="w-full mb-3 rounded-md border-2 border-solid px-3 py-2 @error('email') border-red-500 @enderror" 
                value="{{ old('email') }}" required>
            
            <div class="relative mb-3" x-data="{ showPassword: false }">
                <input name="password" 
                    :type="showPassword ? 'text' : 'password'" 
                    placeholder="Password" 
                    class="w-full rounded-md border-2 border-solid px-3 py-2 pr-10 @error('password') border-red-500 @enderror" 
                    required>
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600"
                        @click="showPassword = !showPassword">
                    
                    <!-- Eye Icon (Show) -->
                    <svg x-show="!showPassword" 
                        class="w-5 h-5" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    
                    <!-- Eye Slash Icon (Hide) -->
                    <svg x-show="showPassword" 
                        class="w-5 h-5" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded cursor-pointer hover:bg-blue-600">Login</button>
        </form>

        <div class="cf-turnstile text-center float mb-3 mt-3" data-sitekey="0x4AAAAAAAxN8pk_QXuP294_" data-callback="javascriptCallback"></div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback" defer></script>

        <div class="mt-4 text-center">
            <div class="text-sm text-gray-500">
                Forgot your password? Please contact <span class="text-blue-600 font-medium"> IT Department</span>.
            </div>
        </div>
    </div>
</main>


@endsection