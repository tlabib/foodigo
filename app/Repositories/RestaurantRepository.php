<?php

namespace App\Repositories;

use App\Models\Restaurant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RestaurantRepository
{
    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator
    {
        return Restaurant::query()
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findWithMenuItems(Restaurant $restaurant): Restaurant
    {
        return $restaurant->load('menuItems');
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

    public function update(Restaurant $restaurant, array $data): Restaurant
    {
        $restaurant->update($data);

        return $restaurant->refresh();
    }

    public function delete(Restaurant $restaurant): void
    {
        $restaurant->delete();
    }
}
