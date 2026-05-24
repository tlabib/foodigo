# Workflow Log

## Purpose
Track implementation progress, commands run, and actions taken during development.

## 2026-05-23
- Added role selection to registration form (`customer` or `rider`).
- Updated registration validation to allow only `customer` and `rider`.
- Saved selected role when creating new users.
- Updated registration tests to cover role-based registration.

### Commands Run
- `php artisan test --compact`
- `git push -u origin dev`
- `git push -u origin feature/auth`

## Template For Future Entries
- Date:
- Task:
- Files changed:
- Commands run:
- Result:

## 2026-05-23 (Role Dashboards)
- Added role-based dashboard routing with redirect logic from `/dashboard`.
- Added separate dashboard pages for `customer`, `rider`, and `admin`.
- Added reusable `role` middleware alias and role-check middleware.
- Updated route protection so each role can only access its own dashboard.
- Added feature tests for dashboard redirects and cross-role access blocking.

### Commands Run
- `php artisan make:middleware EnsureUserHasRole`
- `php artisan make:controller DashboardController`
- `php artisan test --compact --filter=RegistrationTest`

### Notes
- One local test run hit a Windows file-lock error while compiling Blade views (`storage/framework/views` rename access denied). This is environment-specific and not a business-logic failure.

## 2026-05-23 (Admin Restaurant Management Slice)
- Created `restaurants` and `menu_items` domain models with relationships and casts.
- Implemented OOP layers:
  - `RestaurantRepository`, `MenuItemRepository`
  - `RestaurantService`, `MenuItemService`
  - `StoreRestaurantRequest`, `StoreMenuItemRequest`
  - `Admin\RestaurantManagementController`
- Added admin routes for:
  - listing restaurants
  - creating restaurants
  - activate/deactivate toggle
  - adding menu items per restaurant
- Added admin UI page at `admin/restaurants` with forms for restaurant and menu item creation.
- Added factories and feature tests for admin restaurant management.

### Commands Run
- `php artisan make:model Restaurant -m`
- `php artisan make:model MenuItem -m`
- `php artisan make:request StoreRestaurantRequest`
- `php artisan make:request StoreMenuItemRequest`
- `php artisan make:controller Admin/RestaurantManagementController`
- `php artisan make:factory RestaurantFactory --model=Restaurant`
- `php artisan make:factory MenuItemFactory --model=MenuItem`
- `php artisan test --compact --filter=RoleDashboardTest`
- `php artisan test --compact --filter=RegistrationTest`
- Adjusted admin restaurant feature tests to avoid a Windows-specific Blade view compile file-lock flake.
- Verified new admin restaurant actions and role dashboard tests pass.
- Additional commands run:
  - `php artisan view:clear`
  - `php artisan optimize:clear`
  - `php artisan test --compact --filter=AdminRestaurantManagementTest`
  - `php artisan test --compact --filter=RoleDashboardTest`

## 2026-05-23 (Admin Users + Theme Adaptation)
- Added admin users management route and controller.
- Implemented OOP layers for users (`UserRepository`, `UserManagementService`).
- Added admin users page with role filters (`all`, `customer`, `rider`, `admin`) and rider directory panel.
- Updated admin dashboard and restaurant admin page styling to match Figma-inspired coral/gray palette.
- Added feature tests for admin users page access and role filtering.

### Commands Run
- `php artisan test --compact --filter=AdminUserManagementTest`
- `php artisan test --compact --filter=RoleDashboardTest`
- `php artisan test --filter=AdminUserManagementTest`
- `vendor\\bin\\pest tests\\Feature\\AdminUserManagementTest.php`

### Notes
- Admin user tests could not execute in this shell due a local stream output issue (`stream_filter_remove(): Unable to flush filter`).
- Existing role dashboard tests still pass.

## 2026-05-23 (Feature Orders Slice)
- Added ordered migrations for `orders`, `order_items`, `order_status_histories` with FK-safe ordering.
- Built order domain models and relationships (`Order`, `OrderItem`, `OrderStatusHistory`).
- Added OOP order stack: `OrderRepository`, `OrderService`.
- Added requests for customer place-order, admin order update, rider status update.
- Implemented controllers:
  - `Admin\\OrderManagementController`
  - `RiderOrderController`
  - `CustomerOrderController`
- Added routes for admin order listing/detail/update, rider assigned orders/status updates, customer place/track orders.
- Added views for admin orders, rider orders, and customer orders/placement.
- Added feature tests for admin/rider/customer order flows.
- Added factories for order entities.

### Commands Run
- `php artisan make:model Order -m`
- `php artisan make:model OrderItem -m`
- `php artisan make:model OrderStatusHistory -m`
- `php artisan make:factory OrderFactory --model=Order`
- `php artisan make:factory OrderItemFactory --model=OrderItem`
- `php artisan make:factory OrderStatusHistoryFactory --model=OrderStatusHistory`

## 2026-05-23 (Plan + Seeders)
- Updated `plan.md` with explicit remaining TODO items.
- Added `RestaurantMenuSeeder` for 20 restaurants with 3-5 menu items each.
- Added `CustomerSeeder` to create `customer2..customer6` using password `password`.
- Added `RiderSeeder` to create `rider2..rider6` using password `password`.
- Updated `DatabaseSeeder` to call all three seeders.

## 2026-05-23 (Restaurant/Menu Images)
- Added migration to make `restaurants.image` and `menu_items.image` nullable.
- Added image validation rules to create/update requests for restaurants and menu items.
- Added image upload handling to restaurant/menu create and update flows.
- Added image cleanup on restaurant/menu item delete.
- Updated admin restaurant forms to support file upload inputs and previews.

## 2026-05-23 (Image Upload Fix + Visibility)
- Fixed upload reliability in `RestaurantManagementController` by validating uploaded file state before storing.
- Added explicit upload error message when a temporary upload file is invalid/empty.
- Improved admin restaurant list view to show image thumbnails so saved images are immediately visible.
- Added page-level validation error blocks on both restaurant list and restaurant details pages.
- Identified environment issue: `public/storage` symlink is missing, so uploaded images cannot be displayed until linked.
- Added Windows-safe fallback upload path: if `$file->store()` fails, controller now moves the uploaded file directly into `storage/app/public/{directory}` and saves that relative path.

## 2026-05-23 (Welcome Page Restaurants + Pagination)
- Updated `/` route to load active restaurants from database with pagination (`8` per page).
- Replaced default Laravel welcome template with a Foodigo-themed landing page.
- Added restaurant card grid showing image, name, description, rating, and delivery time.
- Added pagination controls on the welcome page.

## 2026-05-23 (Clickable Restaurants + Cart + Order)
- Made welcome-page restaurant cards clickable and linked them to restaurant detail pages.
- Added `RestaurantCatalogController` with:
  - `show()` for public restaurant menu view (active + available items)
  - `addToCart()` for customer cart additions
- Added cart workflow for customers in `CustomerOrderController`:
  - `cart()` view
  - `updateCartItem()` quantity updates
  - `removeCartItem()` item removal
  - upgraded `store()` to place order from cart
- Added new order service method `placeOrderFromCart()` to support multi-item checkout and total calculation.
- Added new views:
  - `resources/views/restaurants/show.blade.php`
  - `resources/views/customer/orders/cart.blade.php`
- Kept backward compatibility in order placement for previous single-item order submit payload.

## 2026-05-23 (Guest Navigation Fix)
- Fixed crash on public restaurant pages for logged-out users.
- Updated `resources/views/layouts/navigation.blade.php` to handle guest/auth states safely:
  - show `Login` / `Register` actions for guests
  - show profile/logout dropdown only for authenticated users
  - avoid direct `Auth::user()->name` access when not logged in

## 2026-05-24 (Auth Branding + Login UX)
- Replaced Laravel logo component with a Foodigo-themed icon in `resources/views/components/application-logo.blade.php`.
- Updated guest auth layout branding in `resources/views/layouts/guest.blade.php`:
  - Foodigo icon color
  - Foodigo text label under icon
  - background aligned with app palette
- Improved login UX in `resources/views/auth/login.blade.php`:
  - added direct `Need an account? Register` link
  - kept forgot-password link and login button in same action row
  - matched login button/focus styling to coral theme

## 2026-05-24 (Login UI Polish)
- Rebuilt `resources/views/auth/login.blade.php` cleanly to fix malformed button markup.
- Made login button full-width to match input width.
- Improved spacing hierarchy between fields, remember-me, button, and helper links.
- Styled helper links for clearer UX:
  - register link as bordered pill CTA
  - forgot-password link as secondary underlined action

## 2026-05-24 (Customer/Admin Dashboard Live Orders)
- Updated `DashboardController` to load real order data for customer and admin dashboards.
- Customer dashboard now shows:
  - quick links (browse, cart, track orders)
  - recent order history list
  - current stage/status, rider assignment, total cost
  - timestamps (placed, updated), delivery address, ordered items, status timeline
- Admin dashboard now shows:
  - recent orders from multiple customers
  - customer, rider, total, current stage, order timings
  - inline quick action form (update status + assign rider)
  - direct link to full order details page
- Extended customer order eager-loading in `OrderRepository` to include rider and order items for richer dashboard display.

## 2026-05-24 (Rider Dashboard Live Deliveries)
- Updated `DashboardController@rider` to load recent assigned deliveries.
- Enhanced rider order eager loading in `OrderRepository` to include:
  - customer email
  - status histories timeline
- Reworked rider dashboard UI to show:
  - quick actions for latest assigned deliveries and full history
  - recent assigned delivery cards with customer/address/total/current stage/timestamps
  - status timeline per delivery
- inline stage update action (`picked_up`, `on_the_way`, `delivered`)

## 2026-05-24 (Status Consistency Across Dashboards)
- Centralized order status definitions in `app/Models/Order.php` with shared helper methods:
  - `allStatuses()`
  - `adminManageableStatuses()`
  - `riderUpdatableStatuses()`
- Updated admin and rider request validation to use centralized status lists:
  - `AdminUpdateOrderRequest`
  - `RiderUpdateOrderStatusRequest`
- Updated admin order pages/controllers to use the same centralized status list, preventing dropdown mismatch.
- Updated rider dashboard status dropdown to render from shared status list, keeping stage updates aligned across admin, rider, and customer views.

## 2026-05-24 (Current vs History Split + Edit Lock)
- Split dashboard order sections for all roles:
  - customer: `Current Orders` + `Order History`
  - admin: `Live Orders` + `Order History`
  - rider: `Recent Assigned Deliveries` + `Delivery History`
- Added terminal status helpers in `Order` model:
  - `historyStatuses()`
  - `currentStatuses()`
  - `isTerminal()`
- Enforced backend lock for terminal orders:
  - admin cannot edit delivered/cancelled orders
  - rider cannot update delivered/cancelled orders
- Dashboard data partitioning now happens in `DashboardController` so delivered/cancelled orders never appear as editable live orders.

## 2026-05-24 (Status Dedup + Currency + Cart Warning)
- Removed duplicate rider/admin delivery phase from active status flow by standardizing on `out_for_delivery` (instead of separate `on_the_way`).
- Added migration `2026_05_24_120001_replace_on_the_way_with_out_for_delivery.php` to update existing rows in:
  - `orders.status`
  - `order_status_histories.status`
- Updated rider status dropdown in `resources/views/rider/orders/index.blade.php` to use `out_for_delivery`.
- Replaced order/cart/admin/customer/rider displayed currency labels from `BDT` to `$`.
- Added cross-restaurant cart warning on `resources/views/restaurants/show.blade.php`:
  - visible warning banner when cart already contains items from another restaurant
  - confirmation prompt before adding item (which clears old restaurant cart)
- Added server-side status flash on cart switch in `RestaurantCatalogController` to inform users when previous cart items are cleared.

## 2026-05-24 (Mock Payment System)
- Added mock payment fields to `orders` via migration:
  - `payment_method`
  - `payment_status`
  - `payment_transaction_ref`
  - `paid_at`
- Added mock payment constants/methods to `Order` model.
- Enhanced checkout in `customer/orders/cart`:
  - payment method selector (COD / Card / Mobile Banking)
  - optional "simulate payment failure" toggle for testing
- Updated `CustomerOrderController@store` to validate and pass payment payload.
- Updated `OrderService::placeOrderFromCart` to process mock payment:
  - COD => `payment_status = pending`
  - online methods => `payment_status = paid` + mock transaction ref + paid time
  - simulated failure for online methods throws validation error and blocks placement
- Added payment details display in customer order list and admin order details page.

## 2026-05-24 (Payment Simplified to COD Only)
- Simplified checkout payment flow to a single method: `cash_on_delivery`.
- Removed card/mobile-banking choices from `customer/orders/cart` checkout UI.
- Removed simulate-payment-failure option from checkout UI and backend flow.
- Updated `CustomerOrderController@store` to always submit COD payment method.
- Updated `OrderService::placeOrderFromCart` to store COD payment as:
  - `payment_method = cash_on_delivery`
  - `payment_status = pending`
  - no transaction reference / paid timestamp

## 2026-05-24 (Admin Dashboard Update Guard)
- In `resources/views/dashboards/admin.blade.php`, disabled the quick-action `Update` button when no rider is assigned for an order.
- Added helper note: "Assign a rider first to enable updates."
