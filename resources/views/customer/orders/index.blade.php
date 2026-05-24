<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">My Orders</h2></x-slot>
    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif
            <div class="flex items-center gap-2">
                <a href="{{ route('customer.cart') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm inline-block">Go to Cart</a>
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-full border border-[#F15B4E] text-[#F15B4E] text-sm inline-block">Browse Restaurants</a>
            </div>
            <div class="space-y-3">
                @forelse ($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm p-4">
                        <p class="font-semibold">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                        <p class="text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $order->status)) }} | ${{ number_format((float) $order->total_price, 2) }}</p>
                        <p class="text-xs text-slate-500">
                            Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }} -
                            {{ ucfirst($order->payment_status) }}
                            @if ($order->payment_transaction_ref)
                                ({{ $order->payment_transaction_ref }})
                            @endif
                        </p>
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
