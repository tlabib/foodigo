<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer Dashboard</h2>
    </x-slot>

    <div class="py-12 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
                <p class="text-gray-900">Welcome, customer. Place a new order or track your previous ones.</p>
                <div class="flex gap-3">
                    <a href="{{ route('customer.orders.create') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm">Place Order</a>
                    <a href="{{ route('customer.orders.index') }}" class="px-4 py-2 rounded-full border border-[#F15B4E] text-[#F15B4E] text-sm">Track My Orders</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
