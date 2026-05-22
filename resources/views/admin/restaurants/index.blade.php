<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin - Restaurants</h2>
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Restaurant</h3>
                <form method="POST" action="{{ route('admin.restaurants.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <x-input-label for="image" :value="__('Restaurant Image (optional)')" />
                        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full text-sm text-slate-600">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-primary-button class="!bg-[#F15B4E] hover:!bg-[#e44f42]">Create Restaurant</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Restaurants</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b">
                                <th class="py-3 pr-4">Name</th>
                                <th class="py-3 pr-4">Image</th>
                                <th class="py-3 pr-4">Status</th>
                                <th class="py-3 pr-4">Delivery Time</th>
                                <th class="py-3 pr-4">Rating</th>
                                <th class="py-3 pr-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($restaurants as $restaurant)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-3 pr-4 font-medium text-slate-800">{{ $restaurant->name }}</td>
                                    <td class="py-3 pr-4">
                                        @if ($restaurant->image)
                                            <img src="{{ asset('storage/'.$restaurant->image) }}" alt="{{ $restaurant->name }}" class="h-12 w-12 rounded-lg object-cover border">
                                        @else
                                            <span class="text-xs text-slate-400">No image</span>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4">
                                        <span class="px-2 py-1 rounded-full text-xs {{ $restaurant->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $restaurant->delivery_time ?: '-' }} min</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $restaurant->rating ?? '-' }}</td>
                                    <td class="py-3 pr-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="px-3 py-1 rounded-full border border-[#F15B4E] text-[#F15B4E]">View</a>

                                            <form method="POST" action="{{ route('admin.restaurants.toggle-active', $restaurant) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-3 py-1 rounded-full border border-slate-200 text-slate-700">
                                                    {{ $restaurant->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.restaurants.destroy', $restaurant) }}" onsubmit="return confirm('Delete this restaurant and all its menu items?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 rounded-full border border-red-200 text-red-600">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-slate-500">No restaurants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $restaurants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
