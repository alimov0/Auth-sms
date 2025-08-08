<?php 

namespace App\DTO\Auth;

class ConfirmPhoneChangeDTO
{
    public string $new_phone;
    public string $code;

    public function __construct(array $data)
    {
        $this->new_phone = $data['new_phone'];
        $this->code = $data['code'];
    }
}
