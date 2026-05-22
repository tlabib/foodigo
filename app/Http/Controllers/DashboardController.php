<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        return match ($user?->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_RIDER => redirect()->route('rider.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }

    public function customer(): View
    {
        return view('dashboards.customer');
    }

    public function rider(): View
    {
        return view('dashboards.rider');
    }

    public function admin(): View
    {
        return view('dashboards.admin');
    }
}
