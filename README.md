# Foodigo

## Tech Stack and Environment
- Laravel 13
- PHP 8.3
- Laragon (local development environment)

## Setup and Run Instructions (Laragon)
1. Open terminal and go to Laragon `www` folder:
```bash
cd C:\laragon\www
```

2. Clone the repository:
```bash
git clone https://github.com/tlabib/foodigo.git
```

3. Enter project folder:
```bash
cd foodigo
```

4. Install PHP and Node dependencies:
```bash
composer install
npm install
```

5. Create `.env` from example:
```bash
copy .env.example .env
```

6. Generate app key:
```bash
php artisan key:generate
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Run the codebase:
```bash
composer run dev
```

## Architecture + OOP/Design Patterns Used
- Architecture: Laravel MVC (`Models`, `Controllers`, `Blade Views`) with layered structure using `Services` and `Repositories`.
- Service Pattern:
  - `OrderService` handles order placement and order status business logic.
  - `RestaurantService`, `MenuItemService`, and `UserManagementService` organize domain logic.
- Repository Pattern:
  - `OrderRepository`, `RestaurantRepository`, `MenuItemRepository`, and `UserRepository` handle data access and query concerns.
- Form Request Validation:
  - Request classes (for example `StoreRestaurantRequest`, `UpdateMenuItemRequest`, `AdminUpdateOrderRequest`) keep validation rules separate from controllers.
- Role-Based Access Control:
  - Middleware (`EnsureUserHasRole`, `EnsureUserIsAdmin`) protects customer/rider/admin routes.
- Testing Approach:
  - Pest feature tests for auth, role access, restaurant management, order flow, and API behavior.

## API Endpoint List
Base URL example: `http://foodigo.test`

Public endpoints:
- `GET /api/restaurants`
- `GET /api/restaurants/{restaurant}/menu-items`

Protected customer endpoints:
- `POST /api/orders`
- `GET /api/orders/{order}`

Security notes:
- Rate limiting is applied on public and authenticated API endpoints.
- Customer order detail endpoint only allows the owner to access their own order.

## Credentials
Admin:
`admin@gmail.com`
`password`

Sample Users:
`customer2@gmail.com`
`customer3@gmail.com`
`customer4@gmail.com`
Password:
`password`

Riders:
`rider2@gmail.com`
`rider3@gmail.com`
`rider4@gmail.com`
`password`
