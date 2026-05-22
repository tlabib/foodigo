<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Restaurant;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $this->orderService->placeOrder(auth()->id(), $request->validated());

        return redirect()->route('customer.orders.index')->with('status', 'Order placed successfully.');
    }
}
