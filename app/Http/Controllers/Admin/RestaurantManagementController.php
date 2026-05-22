<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Models\MenuItem;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use App\Services\MenuItemService;
use App\Services\RestaurantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

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

    public function show(Restaurant $restaurant): View
    {
        return view('admin.restaurants.show', [
            'restaurant' => $this->restaurantService->getRestaurantDetails($restaurant),
        ]);
    }

    public function store(StoreRestaurantRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeUploadedImage($request->file('image'), 'restaurants');
        }

        $this->restaurantService->create($data);

        return back()->with('status', 'Restaurant created successfully.');
    }

    public function toggleActive(Restaurant $restaurant): RedirectResponse
    {
        $this->restaurantService->toggleActive($restaurant);

        return back()->with('status', 'Restaurant activation status updated.');
    }

    public function storeMenuItem(StoreMenuItemRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeUploadedImage($request->file('image'), 'menu-items');
        }

        $this->menuItemService->createForRestaurant($restaurant, $data);

        return back()->with('status', 'Menu item added successfully.');
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $newImagePath = $this->storeUploadedImage($request->file('image'), 'restaurants');
            if ($restaurant->image) {
                Storage::disk('public')->delete($restaurant->image);
            }
            $data['image'] = $newImagePath;
        }

        $this->restaurantService->update($restaurant, $data);

        return back()->with('status', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant): RedirectResponse
    {
        if ($restaurant->image) {
            Storage::disk('public')->delete($restaurant->image);
        }

        foreach ($restaurant->menuItems as $menuItem) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
        }

        $this->restaurantService->delete($restaurant);

        return redirect()->route('admin.restaurants.index')->with('status', 'Restaurant deleted successfully.');
    }

    public function updateMenuItem(UpdateMenuItemRequest $request, Restaurant $restaurant, MenuItem $menuItem): RedirectResponse
    {
        abort_unless((int) $menuItem->restaurant_id === (int) $restaurant->id, 404);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $newImagePath = $this->storeUploadedImage($request->file('image'), 'menu-items');
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $data['image'] = $newImagePath;
        }

        $this->menuItemService->update($menuItem, $data);

        return back()->with('status', 'Menu item updated successfully.');
    }

    public function destroyMenuItem(Restaurant $restaurant, MenuItem $menuItem): RedirectResponse
    {
        abort_unless((int) $menuItem->restaurant_id === (int) $restaurant->id, 404);

        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $this->menuItemService->delete($menuItem);

        return back()->with('status', 'Menu item deleted successfully.');
    }

    public function toggleMenuItemAvailability(Restaurant $restaurant, MenuItem $menuItem): RedirectResponse
    {
        abort_unless((int) $menuItem->restaurant_id === (int) $restaurant->id, 404);

        $this->menuItemService->toggleAvailability($menuItem);

        return back()->with('status', 'Menu item availability updated.');
    }

    private function storeUploadedImage(?UploadedFile $file, string $directory): string
    {
        if (! $file) {
            throw ValidationException::withMessages([
                'image' => 'No image file was received. Please choose a file and retry.',
            ]);
        }

        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'image' => 'Image upload failed: '.$file->getErrorMessage(),
            ]);
        }

        try {
            $stored = $file->store($directory, 'public');
            if (is_string($stored) && $stored !== '') {
                return $stored;
            }
        } catch (Throwable) {
            // Continue to Windows-safe fallback below.
        }

        $targetDirectory = storage_path('app/public/'.$directory);
        File::ensureDirectoryExists($targetDirectory);

        $filename = $file->hashName();
        $file->move($targetDirectory, $filename);

        return trim($directory, '/').'/'.$filename;
    }
}
