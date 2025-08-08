<?php

namespace App\Repositories;

use App\DTO\Auth\RegisterDTO;
use App\Interfaces\Repositories\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AuthRepository implements AuthRepositoryInterface
{
    public function create(RegisterDTO $dto): User
    {
        $avatarPath = null;
        if ($dto->avatar) {
            $avatarPath = Storage::put('avatars', $dto->avatar);
        }

        return User::create([
            'name' => $dto->name,
            'last_name' => $dto->last_name,
            'phone' => $dto->phone,
            'avatar' => $avatarPath,
            'is_verified' => false,
        ]);
    }
}
