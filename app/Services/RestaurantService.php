<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Database\Eloquent\Collection;

class RestaurantService
{
    public function __construct(private readonly RestaurantRepository $restaurantRepository)
    {
    }

    public function getAdminListing(): Collection
    {
        return $this->restaurantRepository->allWithMenuItems();
    }

    public function create(array $data): Restaurant
    {
        return $this->restaurantRepository->create($data);
    }

    public function toggleActive(Restaurant $restaurant): Restaurant
    {
        return $this->restaurantRepository->toggleActive($restaurant);
    }
}
