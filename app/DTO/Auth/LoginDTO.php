<?php

namespace App\DTO\Auth;

class LoginDTO
{
    public string $phone;

    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    public static function fromRequest($request): self
    {
        return new self(
            $request->input('phone')
        );
    }
}
