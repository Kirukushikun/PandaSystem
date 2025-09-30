
<div>
    <!-- Bell -->
    <i class="fa-solid fa-bell text-[25px] cursor-pointer hover:scale-110 relative"
       @click="open = !open; $wire.markAllAsRead()">

        <!-- Red Dot -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 left-[15px] w-3 h-3 bg-red-600 rounded-full border-2 border-white"></span>
        @endif
    </i>
    <div 
        x-show="open" 
        @mouseleave="open = false" 
        x-transition 
        class="absolute right-0 mt-2 w-82 bg-white text-gray-800 rounded-xl shadow-lg z-40 overflow-hidden"
    >
        <div class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 rounded-md">
            <ul class="divide-y divide-gray-200">
                <li class="h-2 bg-blue-600"></li>

                @forelse($notifications as $notif)
                    <li class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex justify-between items-center mb-2">
                            <h1 class="text-sm font-semibold text-gray-900">
                                {{ $notif->status === 'expired' ? 'Allowance Expired' : 'Allowance Reminder' }}
                            </h1>
                            <p class="text-xs text-gray-500 whitespace-nowrap">
                                {{ $notif->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-600 leading-snug">
                            {!! $notif->message !!}
                        </div>
                    </li>
                @empty
                    <li class="p-4 text-sm text-gray-500 text-center">
                        No notifications
                    </li>
                @endforelse

                @if ($notifications->count() < \App\Models\Notification::count())
                    <li>
                        <div wire:click="loadMore" 
                            class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors duration-200 text-sm text-blue-600 font-medium text-center">
                            See More
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>    

</div>


