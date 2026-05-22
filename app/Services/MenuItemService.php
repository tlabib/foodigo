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
}
