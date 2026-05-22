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
}
