<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Repositories\MenuItemRepository;

class MenuItemService
{
    public function __construct(private readonly MenuItemRepository $menuItemRepository)
    {
    }

    public function createForRestaurant(Restaurant $restaurant, array $data): MenuItem
    {
        return $this->menuItemRepository->createForRestaurant($restaurant, $data);
    }

    public function update(MenuItem $menuItem, array $data): MenuItem
    {
        return $this->menuItemRepository->update($menuItem, $data);
    }

    public function delete(MenuItem $menuItem): void
    {
        $this->menuItemRepository->delete($menuItem);
    }

    public function toggleAvailability(MenuItem $menuItem): MenuItem
    {
        return $this->menuItemRepository->toggleAvailability($menuItem);
    }
}
