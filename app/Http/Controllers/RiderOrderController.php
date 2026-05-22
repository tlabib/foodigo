<?php

namespace App\Http\Controllers;

use App\Http\Requests\RiderUpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RiderOrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(): View
    {
        return view('rider.orders.index', [
            'orders' => $this->orderService->riderOrders(auth()->id()),
        ]);
    }

    public function updateStatus(RiderUpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        abort_unless((int) $order->rider_id === (int) auth()->id(), 403);

        $this->orderService->riderUpdateStatus($order, $request->validated()['status']);

        return back()->with('status', 'Order delivery status updated.');
    }
}
