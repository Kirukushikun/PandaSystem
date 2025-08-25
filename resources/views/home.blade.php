@extends('layouts.app')

@section('content')
	<div class="module-container flex gap-5">
        @if(Auth()->user()->access['RQ_Module'] == true)
            <div class="module flex flex-col justify-center bg-blue-100">
                Requestor module
                <a href="/requestor">ENTER</a>
            </div>
        @endif

        @if(Auth()->user()->access['DH_Module'] == true)
            <div class="module flex flex-col justify-center bg-blue-100">
                Division Head module
                <a href="/requestor">ENTER</a>
            </div>
        @endif

        @if(Auth()->user()->access['HRP_Module'] == true)
            <div class="module flex flex-col justify-center bg-blue-100">
                HR Preparation module
                <a href="/requestor">ENTER</a>
            </div>
        @endif

        @if(Auth()->user()->access['HRA_Module'] == true)
            <div class="module flex flex-col justify-center bg-blue-100">
                HR Approval module
                <a href="/requestor">ENTER</a>
            </div>
        @endif

        @if(Auth()->user()->access['FA_Module'] == true)
            <div class="module flex flex-col justify-center bg-blue-100">
                Final Approval module
                <a href="/requestor">ENTER</a>
            </div>
        @endif

    </div>
@endsection