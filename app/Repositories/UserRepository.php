<?php

namespace App\Repositories;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    public function updateVerificationCode(User $user, string $code): User
    {
        $user->verification_code = $code;
        $user->code_sent_at = now();
        $user->save();

        return $user;
    }

    public function changePhone(User $user, string $newPhone, string $code): User
    {
        $user->new_phone = $newPhone;
        $user->verification_code = $code;
        $user->code_sent_at = now();
        $user->phone_verified = false;
        $user->save();

        return $user;
    }

    public function confirmPhone(User $user): User
    {
        $user->phone = $user->new_phone;
        $user->new_phone = null;
        $user->phone_verified = true;
        $user->verification_code = null;
        $user->code_sent_at = null;
        $user->save();

        return $user;
    }

    public function deleteUnverifiedOlderThanDays(int $days): int
    {
        return User::where('phone_verified', false)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}
