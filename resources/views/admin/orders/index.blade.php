<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Admin - Orders</h2>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm p-4 flex flex-wrap gap-2">
                @foreach ($statuses as $status)
                    <a href="{{ route('admin.orders.index', ['status' => $status === 'all' ? null : $status]) }}" class="px-3 py-1 rounded-full text-sm border {{ $selectedStatus === $status ? 'bg-[#F15B4E] text-white border-[#F15B4E]' : 'border-slate-200 text-slate-600' }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </a>
                @endforeach
            </div>

            <div class="space-y-3">
                @forelse ($orders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="block bg-white rounded-2xl shadow-sm p-4 hover:shadow-md transition">
                        <div class="flex flex-wrap justify-between gap-2">
                            <div>
                                <p class="font-semibold text-slate-800">Order #{{ $order->id }} - {{ $order->customer->name }}</p>
                                <p class="text-sm text-slate-500">{{ $order->restaurant->name }} | {{ $order->delivery_address }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                <p class="font-semibold text-slate-800">{{ number_format((float) $order->total_price, 2) }} BDT</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm p-6 text-slate-500">No orders found.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
