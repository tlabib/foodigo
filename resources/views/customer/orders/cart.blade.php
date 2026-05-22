<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">My Cart</h2>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (empty($items))
                <div class="bg-white rounded-2xl shadow-sm p-6 text-slate-500">Your cart is empty.</div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-slate-600 mb-4">Restaurant: <span class="font-semibold text-slate-800">{{ $restaurant?->name }}</span></p>
                    <div class="space-y-3">
                        @foreach ($items as $entry)
                            <div class="border border-slate-100 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div>
                                    <p class="font-semibold">{{ $entry['menu_item']->name }}</p>
                                    <p class="text-sm text-slate-500">{{ number_format((float) $entry['menu_item']->price, 2) }} BDT each</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('customer.cart.items.update', $entry['menu_item']) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" min="0" max="20" value="{{ $entry['quantity'] }}" class="w-20 border-slate-300 rounded-md text-sm">
                                        <button type="submit" class="px-3 py-1 rounded-full border border-slate-200 text-slate-700 text-sm">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('customer.cart.items.destroy', $entry['menu_item']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 rounded-full border border-red-200 text-red-600 text-sm">Remove</button>
                                    </form>
                                </div>
                                <p class="font-semibold text-[#F15B4E]">{{ number_format((float) $entry['line_total'], 2) }} BDT</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <p class="text-lg font-semibold text-slate-800 mb-3">Total: {{ number_format((float) $total, 2) }} BDT</p>
                    <form method="POST" action="{{ route('customer.orders.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <x-input-label for="delivery_address" :value="__('Delivery Address')" />
                            <x-text-input id="delivery_address" name="delivery_address" type="text" class="mt-1 block w-full" required />
                        </div>
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Place Order</x-primary-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
