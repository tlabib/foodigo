<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(): View
    {
        return view('customer.orders.index', [
            'orders' => $this->orderService->customerOrders(auth()->id()),
        ]);
    }

    public function create(): View
    {
        return view('customer.orders.create', [
            'restaurants' => Restaurant::query()->with(['menuItems' => fn ($q) => $q->where('is_available', true)])->where('is_active', true)->get(),
        ]);
    }

    public function cart(): View
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0.0;
        $restaurant = null;

        if (! empty($cart['restaurant_id']) && ! empty($cart['items'])) {
            $restaurant = Restaurant::query()->find($cart['restaurant_id']);

            $menuItems = MenuItem::query()
                ->whereIn('id', array_keys($cart['items']))
                ->where('restaurant_id', $cart['restaurant_id'])
                ->get()
                ->keyBy('id');

            foreach ($cart['items'] as $menuItemId => $quantity) {
                $menuItem = $menuItems->get((int) $menuItemId);
                if (! $menuItem) {
                    continue;
                }

                $lineTotal = ((float) $menuItem->price) * (int) $quantity;
                $total += $lineTotal;

                $items[] = [
                    'menu_item' => $menuItem,
                    'quantity' => (int) $quantity,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return view('customer.orders.cart', [
            'restaurant' => $restaurant,
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function updateCartItem(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart['items'][$menuItem->id])) {
            return back();
        }

        $quantity = (int) $validated['quantity'];
        if ($quantity <= 0) {
            unset($cart['items'][$menuItem->id]);
        } else {
            $cart['items'][$menuItem->id] = $quantity;
        }

        if (empty($cart['items'])) {
            session()->forget('cart');
        } else {
            session()->put('cart', $cart);
        }

        return back()->with('status', 'Cart updated.');
    }

    public function removeCartItem(MenuItem $menuItem): RedirectResponse
    {
        $cart = session()->get('cart', []);
        if (! empty($cart['items'][$menuItem->id])) {
            unset($cart['items'][$menuItem->id]);
        }

        if (empty($cart['items'])) {
            session()->forget('cart');
        } else {
            session()->put('cart', $cart);
        }

        return back()->with('status', 'Item removed from cart.');
    }

    public function store(Request $request): RedirectResponse
    {
        // Backward compatible path for previous single-item order form.
        if ($request->filled('restaurant_id') && $request->filled('menu_item_id') && $request->filled('quantity')) {
            $this->orderService->placeOrder(auth()->id(), $request->validate([
                'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
                'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
                'quantity' => ['required', 'integer', 'min:1', 'max:20'],
                'delivery_address' => ['required', 'string', 'max:255'],
            ]));

            return redirect()->route('customer.orders.index')->with('status', 'Order placed successfully.');
        }

        $validated = $request->validate([
            'delivery_address' => ['required', 'string', 'max:255'],
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart['restaurant_id']) || empty($cart['items'])) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $this->orderService->placeOrderFromCart(auth()->id(), (int) $cart['restaurant_id'], $cart['items'], $validated['delivery_address']);
        session()->forget('cart');

        return redirect()->route('customer.orders.index')->with('status', 'Order placed successfully.');
    }
}
