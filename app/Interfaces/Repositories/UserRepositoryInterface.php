<?php

namespace App\Interfaces\Repositories;

use App\Models\User;
use App\DTO\Auth\CleanUnverifiedUsersDTO;

interface UserRepositoryInterface
{
    public function findByPhone(string $phone): ?User;

    public function deleteUnverifiedUsersOlderThan(CleanUnverifiedUsersDTO $dto): int;
}
