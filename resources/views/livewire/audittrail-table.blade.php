


<div class="flex flex-col gap-5 h-full">
    <div class="table-header flex w-full gap-3 items-center">
        <h1 class="text-[22px] flex-none">Audit Trail</h1>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audits as $audit)
                    <tr>
                        <td>{{$audit->user_id}}</td>
                        <td>{{$audit->name}}</td>
                        <td>{{$audit->module}}</td>
                        <td>{{$audit->action}}</td>
                        <td>{{$audit->created_at->format('m/d/Y')}}</td>
                        <td>{{$audit->created_at->format('h:i A')}}</td>
                    </tr>                
                @endforeach

            </tbody>
        </table>
    </div>

</div>