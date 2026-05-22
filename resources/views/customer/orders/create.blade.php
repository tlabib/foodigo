<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-slate-800 leading-tight">Place Order</h2></x-slot>
    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('customer.orders.store') }}" class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
                @csrf
                <div>
                    <x-input-label for="restaurant_id" :value="__('Restaurant')" />
                    <select id="restaurant_id" name="restaurant_id" class="mt-1 block w-full border-slate-300 rounded-md" required>
                        @foreach ($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="menu_item_id" :value="__('Menu Item')" />
                    <select id="menu_item_id" name="menu_item_id" class="mt-1 block w-full border-slate-300 rounded-md" required>
                        @foreach ($restaurants as $restaurant)
                            @foreach ($restaurant->menuItems as $item)
                                <option value="{{ $item->id }}">{{ $restaurant->name }} - {{ $item->name }} ({{ number_format((float) $item->price, 2) }} BDT)</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="quantity" :value="__('Quantity')" />
                    <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full" min="1" max="20" value="1" required />
                </div>
                <div>
                    <x-input-label for="delivery_address" :value="__('Delivery Address')" />
                    <x-text-input id="delivery_address" name="delivery_address" type="text" class="mt-1 block w-full" required />
                </div>
                <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Place Order</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
