
<div>
    <i class="fa-solid fa-bell text-[25px] cursor-pointer hover:scale-110 relative" @click="open = !open">
        <!-- Notification dot -->
        <span class="absolute -top-1 left-[15px] w-3 h-3 bg-red-600 rounded-full border-2 border-white"></span>
    </i>
    <div 
        x-show="open" 
        @mouseleave="open = false" 
        x-transition 
        class="absolute right-0 mt-2 w-82 bg-white text-gray-800 rounded-xl shadow-lg z-40 overflow-hidden"
    >
        <ul class="divide-y divide-gray-200">
            <li class="h-2 bg-blue-600"></li>
            <li>
                <div 
                    class="p-4 hover:bg-gray-50 transition-colors duration-200"
                >
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-2">
                        <h1 class="text-sm font-semibold text-gray-900">
                            Allowance Expiry Reminder
                        </h1>
                        <p class="text-xs text-gray-500 whitespace-nowrap">
                            Sep 10, 2025
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="text-sm text-gray-600 leading-snug">
                        The allowance under 
                        <a href="#" class="text-blue-600 hover:underline font-medium">PAN-BFC-2025-125</a> 
                        will expire in <b>5 days</b>. Please review and take necessary action.
                    </div>

                    <!-- Status Button -->
                    <div class="flex justify-end">
                        <button 
                            class="text-xs px-3 py-2 mt-1 bg-green-100 text-green-600 rounded-md cursor-pointer hover:bg-green-200 transition"
                        >
                            Mark as Resolved
                        </button>
                    </div>
                </div>
            </li>
            <li>
                <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-2">
                        <h1 class="text-sm font-semibold text-gray-900">
                            Allowance Expired
                        </h1>
                        <p class="text-xs text-gray-500 whitespace-nowrap">
                            Sep 10, 2025
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="text-sm text-gray-600 leading-snug mb-2">
                        The allowance under 
                        <a href="#" class="text-blue-600 hover:underline font-medium">PAN-BFC-2025-612</a> 
                        has expired. Please update the employee’s record if a new PAN is required.
                    </div>

                    <!-- Status Button -->
                    <div class="flex justify-end">
                        <button 
                            class="text-xs px-3 py-2 mt-1 bg-green-100 text-green-600 rounded-md cursor-pointer hover:bg-green-200 transition"
                        >
                            Mark as Resolved
                        </button>
                    </div>
                </div>
            </li>
            <li>
                <div 
                    class="p-4 hover:bg-gray-50 transition-colors duration-200"
                >
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-2">
                        <h1 class="text-sm font-semibold text-gray-900">
                            Allowance Expiry Reminder
                        </h1>
                        <p class="text-xs text-gray-500 whitespace-nowrap">
                            Sep 10, 2025
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="text-sm text-gray-600 leading-snug">
                        The allowance under 
                        <a href="#" class="text-blue-600 hover:underline font-medium">PAN-BFC-2025-946</a> 
                        will expire in <b>2 days</b>. Please review and take necessary action.
                    </div>

                                        <!-- Status Button -->
                    <div class="flex justify-end">
                        <button 
                            class="text-xs px-3 py-2 mt-1 bg-green-100 text-green-600 rounded-md cursor-pointer hover:bg-green-200 transition"
                        >
                            Mark as Resolved
                        </button>
                    </div>
                </div>
            </li>
            <li>
                <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors duration-200 text-sm">
                    <div class="text-blue-600 font-medium">See More</div> 
                </div>
            </li>
        </ul>
    </div>    
</div>
<!-- Notification -->

