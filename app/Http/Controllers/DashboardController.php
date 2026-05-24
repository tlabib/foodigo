<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(): RedirectResponse
    {
        $user = auth()->user();

        return match ($user?->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_RIDER => redirect()->route('rider.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }

    public function customer(): View
    {
        $orders = $this->orderService->customerOrders((int) auth()->id())->take(20);
        [$historyOrders, $currentOrders] = $orders->partition(fn ($order) => $order->isTerminal());

        return view('dashboards.customer', [
            'currentOrders' => $currentOrders->values(),
            'historyOrders' => $historyOrders->values(),
        ]);
    }

    public function rider(): View
    {
        $orders = $this->orderService->riderOrders((int) auth()->id())->take(20);
        [$historyOrders, $currentOrders] = $orders->partition(fn ($order) => $order->isTerminal());

        return view('dashboards.rider', [
            'currentOrders' => $currentOrders->values(),
            'historyOrders' => $historyOrders->values(),
            'riderStatuses' => Order::riderUpdatableStatuses(),
        ]);
    }

    public function admin(): View
    {
        $orders = $this->orderService->adminListing()->take(24);
        [$historyOrders, $currentOrders] = $orders->partition(fn ($order) => $order->isTerminal());

        return view('dashboards.admin', [
            'currentOrders' => $currentOrders->values(),
            'historyOrders' => $historyOrders->values(),
            'riders' => User::query()
                ->where('role', User::ROLE_RIDER)
                ->orderBy('name')
                ->get(['id', 'name']),
            'statuses' => Order::adminManageableStatuses(),
        ]);
    }
}
