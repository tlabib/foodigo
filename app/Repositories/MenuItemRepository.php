<?php

namespace App\Repositories;

use App\Models\MenuItem;
use App\Models\Restaurant;

class MenuItemRepository
{
    public function createForRestaurant(Restaurant $restaurant, array $data): MenuItem
    {
        return $restaurant->menuItems()->create($data);
    }

    public function update(MenuItem $menuItem, array $data): MenuItem
    {
        $menuItem->update($data);

        return $menuItem->refresh();
    }

    public function delete(MenuItem $menuItem): void
    {
        $menuItem->delete();
    }

    public function toggleAvailability(MenuItem $menuItem): MenuItem
    {
        $menuItem->update(['is_available' => ! $menuItem->is_available]);

        return $menuItem->refresh();
    }
}
