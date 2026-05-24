<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-5 space-y-2">
                <p><span class="font-semibold">Customer:</span> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
                <p><span class="font-semibold">Restaurant:</span> {{ $order->restaurant->name }}</p>
                <p><span class="font-semibold">Address:</span> {{ $order->delivery_address }}</p>
                <p><span class="font-semibold">Total:</span> ${{ number_format((float) $order->total_price, 2) }}</p>
                <p><span class="font-semibold">Payment Method:</span> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                <p><span class="font-semibold">Payment Status:</span> {{ ucfirst($order->payment_status) }}</p>
                @if ($order->payment_transaction_ref)
                    <p><span class="font-semibold">Transaction Ref:</span> {{ $order->payment_transaction_ref }}</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 mb-3">Assign Rider & Update Status</h3>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="mt-1 block w-full border-slate-300 rounded-md" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="rider_id" :value="__('Assign Rider')" />
                        <select id="rider_id" name="rider_id" class="mt-1 block w-full border-slate-300 rounded-md">
                            <option value="">Unassigned</option>
                            @foreach ($riders as $rider)
                                <option value="{{ $rider->id }}" @selected((int) $order->rider_id === (int) $rider->id)>
                                    {{ $rider->name }} ({{ $rider->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Update Order</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 mb-3">Items</h3>
                <div class="space-y-2">
                    @foreach ($order->items as $item)
                        <div class="border border-slate-200 rounded-lg px-3 py-2 flex justify-between">
                            <span>{{ $item->menuItem->name }} x{{ $item->quantity }}</span>
                            <span>${{ number_format((float) $item->price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h3 class="font-semibold text-slate-800 mb-3">Status Timeline</h3>
                <div class="space-y-2">
                    @foreach ($order->statusHistories as $history)
                        <p class="text-sm text-slate-700">{{ $history->created_at->format('Y-m-d H:i') }} - {{ ucfirst(str_replace('_', ' ', $history->status)) }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
