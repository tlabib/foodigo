<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(private readonly UserManagementService $userManagementService)
    {
    }

    public function index(Request $request): View
    {
        $roleFilter = $request->string('role')->toString() ?: null;

        return view('admin.users.index', [
            'users' => $this->userManagementService->getUsersForAdmin($roleFilter),
            'riders' => $this->userManagementService->getRiders(),
            'selectedRole' => $roleFilter ?? 'all',
        ]);
    }
}
