<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantCatalogController extends Controller
{
    public function show(Restaurant $restaurant): View
    {
        abort_unless($restaurant->is_active, 404);

        return view('restaurants.show', [
            'restaurant' => $restaurant->load([
                'menuItems' => fn ($query) => $query->where('is_available', true)->orderBy('name'),
            ]),
        ]);
    }

    public function addToCart(Request $request, Restaurant $restaurant, MenuItem $menuItem): RedirectResponse
    {
        abort_unless($restaurant->is_active, 404);
        abort_unless((int) $menuItem->restaurant_id === (int) $restaurant->id && $menuItem->is_available, 404);

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = session()->get('cart', []);

        if (! empty($cart) && (int) ($cart['restaurant_id'] ?? 0) !== (int) $restaurant->id) {
            $cart = [];
        }

        $items = $cart['items'] ?? [];
        $items[$menuItem->id] = ((int) ($items[$menuItem->id] ?? 0)) + $quantity;

        session()->put('cart', [
            'restaurant_id' => $restaurant->id,
            'items' => $items,
        ]);

        return back()->with('status', "{$menuItem->name} added to cart.");
    }
}

