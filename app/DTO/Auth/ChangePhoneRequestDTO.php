<?php

namespace App\DTO\Auth;

class ChangePhoneRequestDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $newPhone
    ) {}

    public static function fromRequest(int $userId, array $data): self
    {
        return new self(
         $userId,
         $data['phone']
        );
    }
}
