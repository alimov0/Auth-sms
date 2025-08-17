<?php

namespace App\DTO\Auth;

class VerifyCodeDTO
{
    public string $phone;
    public string $code;

    public function __construct(array $data)
    {
        $this->phone = $data['phone'];
        $this->code  = $data['code'];
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
