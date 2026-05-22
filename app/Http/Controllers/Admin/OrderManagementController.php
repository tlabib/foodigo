<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdateOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString() ?: null;

        return view('admin.orders.index', [
            'orders' => $this->orderService->adminListing($status),
            'selectedStatus' => $status ?? 'all',
            'statuses' => [
                'all',
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PREPARING,
                Order::STATUS_OUT_FOR_DELIVERY,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ],
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $this->orderService->getAdminOrderDetails($order),
            'riders' => User::query()->where('role', User::ROLE_RIDER)->orderBy('name')->get(['id', 'name', 'email']),
            'statuses' => [
                Order::STATUS_PENDING,
                Order::STATUS_CONFIRMED,
                Order::STATUS_PREPARING,
                Order::STATUS_OUT_FOR_DELIVERY,
                Order::STATUS_DELIVERED,
                Order::STATUS_CANCELLED,
            ],
        ]);
    }

    public function update(AdminUpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $this->orderService->adminUpdateOrder($order, $request->validated());

        return back()->with('status', 'Order updated successfully.');
    }
}
