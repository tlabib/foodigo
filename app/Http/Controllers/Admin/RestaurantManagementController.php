<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use App\Services\MenuItemService;
use App\Services\RestaurantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RestaurantManagementController extends Controller
{
    public function __construct(
        private readonly RestaurantService $restaurantService,
        private readonly MenuItemService $menuItemService,
    ) {
    }

    public function index(): View
    {
        return view('admin.restaurants.index', [
            'restaurants' => $this->restaurantService->getAdminListing(),
        ]);
    }

    public function store(StoreRestaurantRequest $request): RedirectResponse
    {
        $this->restaurantService->create($request->validated());

        return back()->with('status', 'Restaurant created successfully.');
    }

    public function toggleActive(Restaurant $restaurant): RedirectResponse
    {
        $this->restaurantService->toggleActive($restaurant);

        return back()->with('status', 'Restaurant activation status updated.');
    }

    public function storeMenuItem(StoreMenuItemRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $this->menuItemService->createForRestaurant($restaurant, $request->validated());

        return back()->with('status', 'Menu item added successfully.');
    }
}
