<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin - Restaurants') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Restaurant</h3>
                <form method="POST" action="{{ route('admin.restaurants.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="delivery_time" :value="__('Delivery Time (minutes)')" />
                        <x-text-input id="delivery_time" name="delivery_time" type="number" class="mt-1 block w-full" :value="old('delivery_time')" min="1" max="180" />
                        <x-input-error :messages="$errors->get('delivery_time')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="rating" :value="__('Rating (0-5)')" />
                        <x-text-input id="rating" name="rating" type="number" class="mt-1 block w-full" :value="old('rating')" step="0.01" min="0" max="5" />
                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="is_active" :value="__('Status')" />
                        <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="1" @selected(old('is_active', '1') === '1')>Active</option>
                            <option value="0" @selected(old('is_active') === '0')>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">{{ __('Create Restaurant') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                @forelse ($restaurants as $restaurant)
                    <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $restaurant->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $restaurant->description ?: 'No description provided.' }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Status: <span class="font-medium">{{ $restaurant->is_active ? 'Active' : 'Inactive' }}</span>
                                </p>
                            </div>
                            <form method="POST" action="{{ route('admin.restaurants.toggle-active', $restaurant) }}">
                                @csrf
                                @method('PATCH')
                                <x-secondary-button>
                                    {{ $restaurant->is_active ? 'Deactivate' : 'Activate' }}
                                </x-secondary-button>
                            </form>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <h4 class="text-md font-semibold text-gray-900">Add Menu Item</h4>
                            <form method="POST" action="{{ route('admin.restaurants.menu-items.store', $restaurant) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                @csrf
                                <div>
                                    <x-input-label :value="__('Name')" />
                                    <x-text-input name="name" type="text" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label :value="__('Price')" />
                                    <x-text-input name="price" type="number" class="mt-1 block w-full" step="0.01" min="0" required />
                                </div>
                                <div>
                                    <x-input-label :value="__('Availability')" />
                                    <select name="is_available" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="1">Available</option>
                                        <option value="0">Unavailable</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Description')" />
                                    <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="2"></textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <x-primary-button>{{ __('Add Menu Item') }}</x-primary-button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-2">Menu Items</h4>
                            @if ($restaurant->menuItems->isEmpty())
                                <p class="text-sm text-gray-500">No menu items yet.</p>
                            @else
                                <div class="space-y-2">
                                    @foreach ($restaurant->menuItems as $item)
                                        <div class="border rounded-md px-3 py-2 text-sm text-gray-800 flex items-center justify-between">
                                            <span>{{ $item->name }} - {{ number_format((float) $item->price, 2) }} BDT</span>
                                            <span class="text-gray-500">{{ $item->is_available ? 'Available' : 'Unavailable' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-600">
                        No restaurants yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
