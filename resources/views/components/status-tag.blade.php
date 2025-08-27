@php
    $statusColor = [
        'Draft'                => 'bg-gray-100 text-gray-500',    // Neutral: still in progress
        'For Head Approval'    => 'bg-indigo-100 text-indigo-500', // Initial review stage
        'For HR Prep'          => 'bg-blue-100 text-blue-500',    // Preparation phase
        'For Resolution'       => 'bg-yellow-100 text-yellow-600', // Dispute/misalignment handling
        'For Confirmation'     => 'bg-cyan-100 text-cyan-500',    // Division Head confirmation after prep
        'For HR Approval'      => 'bg-teal-100 text-teal-500',    // HR approver review
        'For Final Approval'   => 'bg-orange-100 text-orange-500', // Final sign-off stage
        'Returned'             => 'bg-amber-100 text-amber-600',  // Sent back for correction
        'Returned'             => 'bg-amber-100 text-amber-600',  // Returned to HR for correction
        'Rejected'             => 'bg-red-100 text-red-500',      // Rejected at any stage
        'Approved'             => 'bg-green-100 text-green-600',  // Final approval
        'Withdrew'             => 'bg-purple-100 text-purple-500' // Withdrawn by requestor
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
