<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif

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

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Live Orders (All Customers)</h3>
                    <span class="text-sm text-slate-500">{{ $currentOrders->count() }} current</span>
                </div>

                <div class="space-y-4">
                    @forelse ($currentOrders as $order)
                        <div class="border border-slate-200 rounded-xl p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                                    <p class="text-sm text-slate-600">Customer: {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                                    <p class="text-sm text-slate-600">Rider: {{ $order->rider?->name ?? 'Not assigned' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500">Total</p>
                                    <p class="font-semibold text-[#F15B4E]">${{ number_format((float) $order->total_price, 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600">
                                <p class="{{ $order->status === 'cancelled' ? 'text-red-600 font-semibold' : '' }}">
                                    Current stage: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </p>
                                <p>Placed: {{ $order->created_at?->format('Y-m-d h:i A') }}</p>
                                <p>Last update: {{ $order->updated_at?->format('Y-m-d h:i A') }}</p>
                            </div>

                            <div class="mt-3">
                                <p class="text-sm font-medium text-slate-700 mb-1">Quick Action</p>
                                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="grid grid-cols-1 md:grid-cols-4 gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="border-slate-300 rounded-md text-sm">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" @selected($status === $order->status)>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="rider_id" class="border-slate-300 rounded-md text-sm">
                                        <option value="">Unassigned</option>
                                        @foreach ($riders as $rider)
                                            <option value="{{ $rider->id }}" @selected((int) $order->rider_id === (int) $rider->id)>{{ $rider->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-4 py-2 rounded-md bg-[#F15B4E] text-white text-sm">Update</button>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="px-4 py-2 rounded-md border border-slate-300 text-slate-700 text-sm text-center">Open Details</a>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500">No live orders right now.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-800">Order History</h3>
                    <span class="text-sm text-slate-500">{{ $historyOrders->count() }} completed/cancelled</span>
                </div>

                <div class="space-y-4">
                    @forelse ($historyOrders as $order)
                        <div class="border border-slate-200 rounded-xl p-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">Order #{{ $order->id }} - {{ $order->restaurant->name }}</p>
                                    <p class="text-sm text-slate-600">Customer: {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                                    <p class="text-sm text-slate-600">Rider: {{ $order->rider?->name ?? 'Not assigned' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-500">Total</p>
                                    <p class="font-semibold text-[#F15B4E]">${{ number_format((float) $order->total_price, 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600">
                                <p class="{{ $order->status === 'cancelled' ? 'text-red-600 font-semibold' : '' }}">
                                    Final stage: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </p>
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
