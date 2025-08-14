<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1000)">
    <!-- Loader -->
    <div 
        x-show="loading" 
        x-cloak 
        class="fixed inset-0 flex items-center justify-center bg-white z-50"
    >
        <!-- Smooth spinner -->
        <div class="w-10 h-10 border-4 border-t-blue-500 border-l-gray-300 border-b-gray-300 border-r-gray-300 rounded-full animate-spin"></div>
    </div>
</div>