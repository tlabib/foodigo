<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rider Dashboard</h2>
    </x-slot>

    <div class="py-12 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
                <p class="text-gray-900">Welcome, rider. Manage your assigned deliveries.</p>
                <a href="{{ route('rider.orders.index') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm inline-block">View Assigned Orders</a>
            </div>
        </div>
    </div>
</x-app-layout>
