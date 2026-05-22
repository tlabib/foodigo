<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 text-gray-900 space-y-3">
                    <p>Welcome, admin. Use the quick links below to manage the system.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.restaurants.index') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm">
                            Manage Restaurants & Menu
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-full border border-[#F15B4E] text-[#F15B4E] text-sm">
                            Manage Users & Riders
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-full border border-slate-200 text-slate-700 text-sm">
                            Manage Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
