<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodigoApiController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function restaurants(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 10), 50));

        $restaurants = Restaurant::query()
            ->active()
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json($restaurants);
    }

    public function restaurantMenuItems(Restaurant $restaurant): JsonResponse
    {
        if (! $restaurant->is_active) {
            abort(404);
        }

        $menuItems = $restaurant->menuItems()
            ->where('is_available', true)
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
            ],
            'menu_items' => $menuItems,
        ]);
    }

    public function storeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'delivery_address' => ['required', 'string', 'max:255'],
        ]);

        $order = $this->orderService->placeOrder((int) $request->user()->id, $validated);

        return response()->json([
            'message' => 'Order placed successfully.',
            'order' => $order->load(['items.menuItem', 'statusHistories']),
        ], 201);
    }

    public function showOwnOrder(Request $request, Order $order): JsonResponse
    {
        if ((int) $order->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        return response()->json([
            'order' => $order->load(['restaurant', 'rider', 'items.menuItem', 'statusHistories']),
        ]);
    }
}
