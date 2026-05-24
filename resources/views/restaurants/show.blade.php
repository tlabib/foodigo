<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">{{ $restaurant->name }}</h2>
            @auth
                @if (auth()->user()->role === \App\Models\User::ROLE_CUSTOMER)
                    <a href="{{ route('customer.cart') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm font-semibold">Cart</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif
            @if (!empty($cartRestaurantName))
                <div class="bg-amber-50 text-amber-800 border border-amber-200 px-4 py-3 rounded-xl">
                    Your cart currently has items from <span class="font-semibold">{{ $cartRestaurantName }}</span>.
                    Adding items from this restaurant will clear previous cart items.
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="h-60 bg-slate-100">
                    @if ($restaurant->image)
                        <img src="{{ asset('storage/'.$restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="p-6">
                    <p class="text-slate-600">{{ $restaurant->description ?: 'Fresh food and quick delivery.' }}</p>
                    <div class="flex gap-6 mt-4 text-sm text-slate-600">
                        <span>Rating: {{ $restaurant->rating ?? 'N/A' }}</span>
                        <span>Delivery: {{ $restaurant->delivery_time ? $restaurant->delivery_time.' min' : 'Time soon' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Menu Items</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($restaurant->menuItems as $item)
                        <div class="border border-slate-100 rounded-2xl p-4">
                            <div class="h-32 bg-slate-100 rounded-xl overflow-hidden mb-3">
                                @if ($item->image)
                                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <h4 class="font-semibold">{{ $item->name }}</h4>
                            <p class="text-sm text-slate-500 mt-1">{{ $item->description ?: 'Delicious choice.' }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="font-semibold text-[#F15B4E]">${{ number_format((float) $item->price, 2) }}</span>
                                @auth
                                    @if (auth()->user()->role === \App\Models\User::ROLE_CUSTOMER)
                                        <form method="POST" action="{{ route('customer.cart.add', [$restaurant, $item]) }}" class="flex items-center gap-2"
                                              @if (!empty($cartRestaurantName)) onsubmit="return confirm('Your current cart has items from another restaurant. Continue and clear them?')" @endif>
                                            @csrf
                                            <input type="number" name="quantity" min="1" max="20" value="1" class="w-16 border-slate-300 rounded-md text-sm">
                                            <button type="submit" class="px-3 py-1 rounded-full bg-[#F15B4E] text-white text-sm">Add</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="px-3 py-1 rounded-full border border-[#F15B4E] text-[#F15B4E] text-sm">Login to Order</a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500">No available menu items right now.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
