<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RestaurantService
{
    public function __construct(private readonly RestaurantRepository $restaurantRepository)
    {
    }

    public function getAdminListing(): LengthAwarePaginator
    {
        return $this->restaurantRepository->paginateForAdmin();
    }

    public function getRestaurantDetails(Restaurant $restaurant): Restaurant
    {
        return $this->restaurantRepository->findWithMenuItems($restaurant);
    }

    public function create(array $data): Restaurant
    {
        return $this->restaurantRepository->create($data);
    }

    public function toggleActive(Restaurant $restaurant): Restaurant
    {
        return $this->restaurantRepository->toggleActive($restaurant);
    }

    public function update(Restaurant $restaurant, array $data): Restaurant
    {
        return $this->restaurantRepository->update($restaurant, $data);
    }

    public function delete(Restaurant $restaurant): void
    {
        $this->restaurantRepository->delete($restaurant);
    }
}
