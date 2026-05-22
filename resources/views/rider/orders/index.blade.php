<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Assigned Orders</h2></x-slot>
    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif
            @forelse ($orders as $order)
                <div class="bg-white rounded-2xl shadow-sm p-4 space-y-3">
                    <div>
                        <p class="font-semibold">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                        <p class="text-sm text-slate-600">Customer: {{ $order->customer->name }} | {{ $order->delivery_address }}</p>
                    </div>
                    <form method="POST" action="{{ route('rider.orders.update-status', $order) }}" class="flex items-center gap-3">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border-slate-300 rounded-md" required>
                            <option value="picked_up">Picked Up</option>
                            <option value="on_the_way">On The Way</option>
                            <option value="delivered">Delivered</option>
                        </select>
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Update</x-primary-button>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm p-6 text-slate-500">No assigned orders yet.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
