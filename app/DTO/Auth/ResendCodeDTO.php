<?php

namespace App\DTO\Auth;

class ResendCodeDTO
{
    public function __construct(
        public readonly string $phone
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            phone: $data['phone']
        );
    }
}
