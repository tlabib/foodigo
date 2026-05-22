<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantMenuSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $menuNames = [
            'Chicken Burger',
            'Beef Burger',
            'Pizza Slice',
            'Fried Chicken',
            'Pasta',
            'Grilled Sandwich',
            'French Fries',
            'Noodles',
            'Chicken Wrap',
            'Rice Bowl',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $restaurant = Restaurant::query()->updateOrCreate(
                ['name' => "Restaurant {$i}"],
                [
                    'description' => "Popular food spot {$i}",
                    'delivery_time' => random_int(15, 45),
                    'rating' => number_format(random_int(35, 50) / 10, 2),
                    'is_active' => true,
                ]
            );

            $itemsCount = random_int(3, 5);

            for ($j = 1; $j <= $itemsCount; $j++) {
                $name = $menuNames[array_rand($menuNames)]." {$j}";

                MenuItem::query()->updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'name' => $name,
                    ],
                    [
                        'description' => "Tasty {$name} from {$restaurant->name}",
                        'price' => random_int(120, 650),
                        'is_available' => true,
                    ]
                );
            }
        }
    }
}
