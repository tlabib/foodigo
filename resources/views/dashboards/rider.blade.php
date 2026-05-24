<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rider Dashboard</h2>
    </x-slot>

    <div class="py-12 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-3">
                <p class="text-gray-900">Welcome, rider. Manage your assigned deliveries.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('rider.orders.index') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm inline-block">Latest Assigned Deliveries</a>
                    {{-- <a href="{{ route('rider.orders.index') }}" class="px-4 py-2 rounded-full border border-[#F15B4E] text-[#F15B4E] text-sm inline-block">All Delivery History</a> --}}
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Recent Assigned Deliveries</h3>
                    <span class="text-sm text-slate-500">{{ $currentOrders->count() }} current</span>
                </div>

                <div class="space-y-4">
                    @forelse ($currentOrders as $order)
                        <div class="border border-slate-200 rounded-xl p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                                    <p class="text-sm text-slate-600">Customer: {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                                    <p class="text-sm text-slate-600">Address: {{ $order->delivery_address }}</p>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1">
                                            Payment: COD
                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500">Total</p>
                                    <p class="font-semibold text-[#F15B4E]">${{ number_format((float) $order->total_price, 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600">
                                <p>Current stage: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                <p>Placed: {{ $order->created_at?->format('Y-m-d h:i A') }}</p>
                                <p>Last update: {{ $order->updated_at?->format('Y-m-d h:i A') }}</p>
                            </div>

                            <div class="mt-3">
                                <p class="text-sm font-medium text-slate-700 mb-1">Status Timeline</p>
                                @foreach ($order->statusHistories as $history)
                                    <p class="text-xs text-slate-500">
                                        {{ $history->created_at?->format('Y-m-d h:i A') }} - {{ ucfirst(str_replace('_', ' ', $history->status)) }}
                                    </p>
                                @endforeach
                            </div>

                            <form method="POST" action="{{ route('rider.orders.update-status', $order) }}" class="mt-3 flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="border-slate-300 rounded-md text-sm" required>
                                    @foreach ($riderStatuses as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-4 py-2 rounded-md bg-[#F15B4E] text-white text-sm">Update Stage</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-slate-500">No active deliveries right now.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Delivery History</h3>
                    <span class="text-sm text-slate-500">{{ $historyOrders->count() }} completed/cancelled</span>
                </div>

                <div class="space-y-4">
                    @forelse ($historyOrders as $order)
                        <div class="border border-slate-200 rounded-xl p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                                    <p class="text-sm text-slate-600">Customer: {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                                    <p class="text-sm text-slate-600">Address: {{ $order->delivery_address }}</p>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1">
                                            Payment: COD
                                        </span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500">Total</p>
                                    <p class="font-semibold text-[#F15B4E]">${{ number_format((float) $order->total_price, 2) }}</p>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600">
                                <p>Final stage: {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                                <p>Placed: {{ $order->created_at?->format('Y-m-d h:i A') }}</p>
                                <p>Last update: {{ $order->updated_at?->format('Y-m-d h:i A') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500">No history yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
