<?php 

namespace App\DTO\Auth;

class LoginDTO
{
    public string $phone;

    public function __construct(array $data)
    {
        $this->phone = $data['phone'];
    }
}
