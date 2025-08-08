<?php

namespace App\Repositories;

use App\Models\User;
use App\DTO\Auth\CleanUnverifiedUsersDTO;
use App\Interfaces\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
   
    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    
    public function deleteUnverifiedUsersOlderThan(CleanUnverifiedUsersDTO $dto): int
    {
        return User::where('is_verified', false)
            ->where('created_at', '<', $dto->thresholdDate)
            ->delete();
    }
}
