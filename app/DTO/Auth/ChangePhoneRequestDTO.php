<?php

namespace App\DTO\Auth;

class ChangePhoneRequestDTO
{
    public string $new_phone;

    public function __construct(array $data)
    {
        $this->new_phone = $data['new_phone'];
    }
}
