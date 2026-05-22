<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">My Orders</h2></x-slot>
    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif
            <a href="{{ route('customer.orders.create') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm inline-block">Place New Order</a>
            <div class="space-y-3">
                @forelse ($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm p-4">
                        <p class="font-semibold">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                        <p class="text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $order->status)) }} | {{ number_format((float) $order->total_price, 2) }} BDT</p>
                        <div class="mt-2">
                            @foreach ($order->statusHistories as $history)
                                <p class="text-xs text-slate-500">{{ $history->created_at->format('Y-m-d H:i') }} - {{ ucfirst(str_replace('_', ' ', $history->status)) }}</p>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm p-6 text-slate-500">No orders yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
