


<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Access Logs</h1>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User Email</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statuses = [
                        'success' => 'bg-green-100 text-green-500',
                        'failure' => 'bg-red-100 text-red-500',
                    ];
                @endphp
                @foreach($logs as $log)
                    @php
                        $statusText = $log->success == '1' ? 'success': 'failure';
                        $statusColor = $statuses[$statusText];
                    @endphp
                    <tr>
                        <td>{{$log->email}}</td>
                        <td>
                            <div class="status-tag {{ $statusColor }} capitalize">{{ $statusText }}</div>
                        </td>
                        <td>{{$log->ip_address}}</td>
                        <td>{{$log->created_at->format('m/d/Y')}}</td>
                        <td>{{$log->created_at->format('m:h A')}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-pagination :paginator="$logs" />

</div>