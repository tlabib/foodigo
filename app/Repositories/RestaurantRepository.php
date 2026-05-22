<?php

namespace App\Repositories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;

class RestaurantRepository
{
    public function allWithMenuItems(): Collection
    {
        return Restaurant::query()
            ->with('menuItems')
            ->orderByDesc('id')
            ->get();
    }

    public function create(array $data): Restaurant
    {
        return Restaurant::query()->create($data);
    }

    public function toggleActive(Restaurant $restaurant): Restaurant
    {
        $restaurant->update(['is_active' => ! $restaurant->is_active]);

        return $restaurant->refresh();
    }
}
