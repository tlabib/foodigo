<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Foodigo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f5f6f8] text-slate-800">
    <div class="min-h-screen">
        <header class="bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[#F15B4E]">Foodigo</h1>
                    <p class="text-sm text-slate-500">Discover your next favorite meal</p>
                </div>
                @if (Route::has('login'))
                    <div class="flex items-center gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm font-semibold hover:bg-[#e44f42]">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 rounded-full border border-[#F15B4E] text-[#F15B4E] text-sm font-semibold hover:bg-[#fff0ee]">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-[#F15B4E] text-white text-sm font-semibold hover:bg-[#e44f42]">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold">Restaurants</h2>
                <p class="text-slate-500 mt-1">Browse available places near you.</p>
            </div>

            @if ($restaurants->isEmpty())
                <div class="bg-white rounded-2xl p-8 shadow-sm text-center text-slate-500">
                    No active restaurants found yet.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($restaurants as $restaurant)
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="block bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100 hover:shadow-md transition">
                            <div class="h-40 bg-slate-100">
                                @if ($restaurant->image)
                                    <img src="{{ asset('storage/'.$restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No image</div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg leading-tight">{{ $restaurant->name }}</h3>
                                <p class="text-slate-500 text-sm mt-1">{{ $restaurant->description ?: 'Fresh food and quick delivery.' }}</p>
                                <div class="flex items-center justify-between mt-4 text-sm">
                                    <span class="text-[#F15B4E] font-semibold">⭐ {{ $restaurant->rating ?? 'N/A' }}</span>
                                    <span class="text-slate-500">{{ $restaurant->delivery_time ? $restaurant->delivery_time.' min' : 'Time soon' }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $restaurants->links() }}
                </div>
            @endif
        </main>
    </div>
</body>
</html>
