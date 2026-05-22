<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function getAllForAdmin(?string $role = null): Collection
    {
        return User::query()
            ->when($role, fn ($query) => $query->where('role', $role))
            ->orderByDesc('id')
            ->get();
    }

    public function getRiders(): Collection
    {
        return User::query()
            ->where('role', User::ROLE_RIDER)
            ->orderBy('name')
            ->get();
    }
}
