<?php 

namespace App\Interfaces\Repositories;

use App\DTO\Auth\RegisterDTO;
use App\Models\User;

interface AuthRepositoryInterface
{
    public function create(RegisterDTO $dto): User;
}
