<?php

namespace App\Repositories;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function adminListing(?string $status = null): Collection
    {
        return Order::query()
            ->with(['customer:id,name,email', 'rider:id,name,email', 'restaurant:id,name'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();
    }

    public function findForAdmin(Order $order): Order
    {
        return $order->load([
            'customer:id,name,email',
            'rider:id,name,email',
            'restaurant:id,name',
            'items.menuItem:id,name,price',
            'statusHistories',
        ]);
    }

    public function create(array $data): Order
    {
        return Order::query()->create($data);
    }

    public function addItem(Order $order, MenuItem $menuItem, int $quantity): void
    {
        $order->items()->create([
            'menu_item_id' => $menuItem->id,
            'quantity' => $quantity,
            'price' => $menuItem->price,
        ]);
    }

    public function createStatusHistory(Order $order, string $status): OrderStatusHistory
    {
        return $order->statusHistories()->create(['status' => $status]);
    }

    public function updateForAdmin(Order $order, array $data): Order
    {
        $order->update($data);

        return $order->refresh();
    }

    public function riderOrders(int $riderId): Collection
    {
        return Order::query()
            ->with(['customer:id,name', 'restaurant:id,name'])
            ->where('rider_id', $riderId)
            ->latest()
            ->get();
    }

    public function customerOrders(int $customerId): Collection
    {
        return Order::query()
            ->with(['restaurant:id,name', 'statusHistories'])
            ->where('user_id', $customerId)
            ->latest()
            ->get();
    }
}
