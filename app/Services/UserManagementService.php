<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserManagementService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function getUsersForAdmin(?string $role = null): Collection
    {
        $allowedRoles = [
            User::ROLE_ADMIN,
            User::ROLE_CUSTOMER,
            User::ROLE_RIDER,
        ];

        if ($role !== null && ! in_array($role, $allowedRoles, true)) {
            $role = null;
        }

        return $this->userRepository->getAllForAdmin($role);
    }

    public function getRiders(): Collection
    {
        return $this->userRepository->getRiders();
    }
}
