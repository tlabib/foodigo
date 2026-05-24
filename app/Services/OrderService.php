<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private readonly OrderRepository $orderRepository)
    {
    }

    public function adminListing(?string $status = null): Collection
    {
        return $this->orderRepository->adminListing($status);
    }

    public function getAdminOrderDetails(Order $order): Order
    {
        return $this->orderRepository->findForAdmin($order);
    }

    public function placeOrder(int $customerId, array $payload): Order
    {
        return DB::transaction(function () use ($customerId, $payload) {
            $menuItem = MenuItem::query()->findOrFail($payload['menu_item_id']);

            if ((int) $menuItem->restaurant_id !== (int) $payload['restaurant_id']) {
                throw ValidationException::withMessages([
                    'menu_item_id' => 'Selected menu item does not belong to the selected restaurant.',
                ]);
            }

            $order = $this->orderRepository->create([
                'user_id' => $customerId,
                'restaurant_id' => $payload['restaurant_id'],
                'status' => Order::STATUS_PENDING,
                'delivery_address' => $payload['delivery_address'],
                'total_price' => 0,
            ]);

            $quantity = (int) $payload['quantity'];
            $this->orderRepository->addItem($order, $menuItem, $quantity);
            $total = ((float) $menuItem->price * $quantity);

            $this->orderRepository->updateForAdmin($order, ['total_price' => $total]);
            $this->orderRepository->createStatusHistory($order, Order::STATUS_PENDING);

            return $order->refresh();
        });
    }

    public function placeOrderFromCart(int $customerId, int $restaurantId, array $cartItems, string $deliveryAddress): Order
    {
        return DB::transaction(function () use ($customerId, $restaurantId, $cartItems, $deliveryAddress) {
            $menuItems = MenuItem::query()
                ->whereIn('id', array_keys($cartItems))
                ->where('restaurant_id', $restaurantId)
                ->where('is_available', true)
                ->get()
                ->keyBy('id');

            if ($menuItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart has no valid items.',
                ]);
            }

            $order = $this->orderRepository->create([
                'user_id' => $customerId,
                'restaurant_id' => $restaurantId,
                'status' => Order::STATUS_PENDING,
                'delivery_address' => $deliveryAddress,
                'total_price' => 0,
            ]);

            $total = 0.0;
            foreach ($cartItems as $menuItemId => $quantity) {
                $menuItem = $menuItems->get((int) $menuItemId);
                if (! $menuItem) {
                    continue;
                }

                $qty = max(1, min(20, (int) $quantity));
                $this->orderRepository->addItem($order, $menuItem, $qty);
                $total += ((float) $menuItem->price * $qty);
            }

            $this->orderRepository->updateForAdmin($order, ['total_price' => $total]);
            $this->orderRepository->createStatusHistory($order, Order::STATUS_PENDING);

            return $order->refresh();
        });
    }

    public function adminUpdateOrder(Order $order, array $payload): Order
    {
        if ($order->isTerminal()) {
            throw ValidationException::withMessages([
                'status' => 'Delivered or cancelled orders are locked and cannot be edited.',
            ]);
        }

        $updated = $this->orderRepository->updateForAdmin($order, [
            'status' => $payload['status'],
            'rider_id' => $payload['rider_id'] ?? $order->rider_id,
        ]);

        $this->orderRepository->createStatusHistory($updated, $payload['status']);

        return $updated;
    }

    public function riderUpdateStatus(Order $order, string $status): Order
    {
        if ($order->isTerminal()) {
            throw ValidationException::withMessages([
                'status' => 'Delivered or cancelled orders are locked and cannot be updated.',
            ]);
        }

        $updated = $this->orderRepository->updateForAdmin($order, ['status' => $status]);
        $this->orderRepository->createStatusHistory($updated, $status);

        return $updated;
    }

    public function riderOrders(int $riderId): Collection
    {
        return $this->orderRepository->riderOrders($riderId);
    }

    public function customerOrders(int $customerId): Collection
    {
        return $this->orderRepository->customerOrders($customerId);
    }
}
