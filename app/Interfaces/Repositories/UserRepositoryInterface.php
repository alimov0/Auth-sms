<?php

namespace App\Interfaces\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByPhone(string $phone): ?User;

    public function updateVerificationCode(User $user, string $code): User;
}
