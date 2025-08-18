@php
    $statusColor = [
        'Draft' => 'bg-gray-100 text-gray-500',
        'For Prep' => 'bg-blue-100 text-blue-500',
        'Returned' => 'bg-yellow-100 text-yellow-600',
        'For Approval' => 'bg-orange-100 text-orange-500',
        'Rejected' => 'bg-red-100 text-red-500',
        'Approved' => 'bg-green-100 text-green-600',
        'Withdrew' => 'bg-purple-100 text-purple-500',
    ];
@endphp

@if($statusLocation == 'Container')
    <!-- For Containers tag -->
    <div class="absolute top-[35px] right-[40px] flex gap-3">
        <div class="request-status {{$statusColor[$statusText]}}">
            {{ $statusText }}
        </div>
    </div>
@else
    <!-- For Table tag -->
    <div class="status-tag {{ $statusColor[$statusText] }}">{{ $statusText }}</div>
@endif
