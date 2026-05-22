<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Restaurant Details</h2>
            <a href="{{ route('admin.restaurants.index') }}" class="px-4 py-2 rounded-full border border-slate-200 text-slate-700 text-sm">Back to List</a>
        </div>
    </x-slot>

    <div class="py-8 bg-[#f5f6f8] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-[#ffe3df] text-[#F15B4E] px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl">
                    <p class="font-medium">Please fix the following:</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Restaurant</h3>
                <form method="POST" action="{{ route('admin.restaurants.update', $restaurant) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $restaurant->name)" required />
                    </div>
                    <div>
                        <x-input-label for="delivery_time" :value="__('Delivery Time (minutes)')" />
                        <x-text-input id="delivery_time" name="delivery_time" type="number" class="mt-1 block w-full" :value="old('delivery_time', $restaurant->delivery_time)" min="1" max="180" />
                    </div>
                    <div>
                        <x-input-label for="rating" :value="__('Rating (0-5)')" />
                        <x-text-input id="rating" name="rating" type="number" class="mt-1 block w-full" :value="old('rating', $restaurant->rating)" step="0.01" min="0" max="5" />
                    </div>
                    <div>
                        <x-input-label for="is_active" :value="__('Status')" />
                        <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="1" @selected((string) old('is_active', (int) $restaurant->is_active) === '1')>Active</option>
                            <option value="0" @selected((string) old('is_active', (int) $restaurant->is_active) === '0')>Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('description', $restaurant->description) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="image" :value="__('Restaurant Image (optional)')" />
                        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full text-sm text-slate-600">
                        @if ($restaurant->image)
                            <img src="{{ asset('storage/'.$restaurant->image) }}" alt="{{ $restaurant->name }}" class="mt-3 h-20 w-20 rounded-lg object-cover border">
                        @endif
                    </div>
                    <div class="md:col-span-2 flex flex-wrap gap-2">
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Update Restaurant</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Menu Item</h3>
                <form method="POST" action="{{ route('admin.restaurants.menu-items.store', $restaurant) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <x-input-label :value="__('Menu Item Image (optional)')" />
                        <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full text-sm text-slate-600">
                    </div>
                    <div class="md:col-span-2">
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Add Menu Item</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Items</h3>
                <div class="space-y-4">
                    @forelse ($restaurant->menuItems as $item)
                        <div class="border border-slate-200 rounded-xl p-4 space-y-3">
                            <form method="POST" action="{{ route('admin.restaurants.menu-items.update', [$restaurant, $item]) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <x-input-label :value="__('Name')" />
                                    <x-text-input name="name" type="text" class="mt-1 block w-full" :value="$item->name" required />
                                </div>
                                <div>
                                    <x-input-label :value="__('Price')" />
                                    <x-text-input name="price" type="number" class="mt-1 block w-full" step="0.01" min="0" :value="$item->price" required />
                                </div>
                                <div>
                                    <x-input-label :value="__('Availability')" />
                                    <select name="is_available" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="1" @selected($item->is_available)>Available</option>
                                        <option value="0" @selected(! $item->is_available)>Unavailable</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Description')" />
                                    <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="2">{{ $item->description }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Menu Item Image (optional)')" />
                                    <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full text-sm text-slate-600">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="mt-3 h-16 w-16 rounded-lg object-cover border">
                                    @endif
                                </div>
                                <div class="md:col-span-2 flex flex-wrap gap-2">
                                    <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Update Item</x-primary-button>
                                </div>
                            </form>

                            <div class="flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.restaurants.menu-items.toggle-availability', [$restaurant, $item]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1 rounded-full border border-slate-200 text-slate-700 text-sm">
                                        {{ $item->is_available ? 'Deactivate' : 'Activate' }} Item
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.restaurants.menu-items.destroy', [$restaurant, $item]) }}" onsubmit="return confirm('Delete this menu item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 rounded-full border border-red-200 text-red-600 text-sm">Delete Item</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm">No menu items yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
