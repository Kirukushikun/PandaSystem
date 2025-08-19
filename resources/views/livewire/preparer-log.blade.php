<section class="content-block" id="content-block-3">
    <h1 class="text-[22px]">Return & Correction Log</h1>
    <div class="log-container">
        @php
            $header = [
                'requestor' => 'Resubmitted by Division Head (Requestor)',
                'preparer' => 'Returned by HR (Preparer)',
                'approver' => 'Returned by Atty (Approver)'
            ]
        @endphp
        @forelse($logs as $log)
            <div class="log-item border-b-2 border-gray-300 pb-5 mb-5">
                <div class="log-header flex flex-col mb-4 md:flex-row md:justify-between md:items-center md:mb-0">
                    <div class="log-issue text-[17px] font-bold">{{$header[$log->origin]}}</div>
                    <div class="log-date">{{$log->created_at->format('M d, Y | h:m A')}}</div>
                </div>
                <div class="log-content">
                    <h1>Reason: {{$log->reason}}</h1>
                    <p>Details: {{$log->details}}</p>
                </div>
            </div>
        @empty 
            <div class="empty-promt w-full h-[200px] flex flex-col items-center justify-center rounded text-gray-400 select-none">
                <i class="fa-solid fa-box-open text-xl mb-1"></i>
                <p class="text-lg">There is no correction entry yet</p>
            </div>
        @endforelse
    </div>
</section>