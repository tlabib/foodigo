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

        $cart = session()->get('cart', []);
        $cartRestaurantId = (int) ($cart['restaurant_id'] ?? 0);
        $cartRestaurantName = null;
        if ($cartRestaurantId > 0 && $cartRestaurantId !== (int) $restaurant->id) {
            $cartRestaurantName = Restaurant::query()->whereKey($cartRestaurantId)->value('name');
        }

        return view('restaurants.show', [
            'restaurant' => $restaurant->load([
                'menuItems' => fn ($query) => $query->where('is_available', true)->orderBy('name'),
            ]),
            'cartRestaurantId' => $cartRestaurantId,
            'cartRestaurantName' => $cartRestaurantName,
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
            session()->flash('status', 'Cart switched to this restaurant. Previous restaurant items were cleared.');
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
